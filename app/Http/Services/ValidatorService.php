<?php


namespace App\Http\Services;


use App\Ingredient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ValidatorService
{
   public function validateAlreadyExistsIngredient($request) {
      $rules = [
         'ingredients.name' => ['required', 'min:2', 'max:20'],
         'ingredients.quantity' => ['required', 'between:1,50000', 'integer', ],
         'ingredients.unit_type' => ['required', 'min:1', 'max:30'],
         'ingredients.unit_price' => ['required', 'between:1,50000', 'integer', ]
      ];

      $message = [
         'ingredients.name.required' => 'Kötelező nevet megadni!',
         'ingredients.name.min' => 'Legalább :min karakter hosszú nevet adj meg!',
         'ingredients.name.max' => 'Ne kisregény legyen az alapanyag neve! Max :max karakter!',
         'ingredients.quantity.between' => 'Kérlek ne rizsszemenként töltsd fel a leltárat, köszi!',
         'ingredients.quantity.integer' => 'Kérlek ne rizsszemenként töltsd fel a leltárat, köszi! ',
         'ingredients.unit_type.required' => 'Kötelező típust megadni!',
         'ingredients.unit_type.min' => 'Legalább :min karakter hosszú legyen az egységtípus!',
         'ingredients.unit_type.max' => 'Max :max karakter lehet az egységtípus!',
         'ingredients.unit_price.required' => 'Ne hackeld az oldalt pls, backenden is van validáció!',
         'ingredients.unit_price.integer' => 'Úgy látom már nem is integer típusú a számunk.. ennyire tuti, hogy nem drága az alapanyag :/',
         'ingredients.unit_price.between' => ':min és :max közötti számot adj meg kérlek!',
      ];

      $validator = Validator::make($request, $rules, $message);

      if( $validator->fails()) {
         return $validator->errors();
      }

      return true;
   }

   public function validateNewIngredient($request) {
      /*
       * Ha ajax postként object érkezik a Laravel validátorába, akkor azt a Validator osztály képes kezelni.
       * Először asszociatív tömbbé alakítja a requestet, majd - mintha JavaScriptben lennénk - ponttal (.)
       * tudunk hivatkozni az objektumunk egyik mezőjére:  'ingredients.name'
       *
       * Fontos!: itt mindent manuálisan kell megadnunk! Tehát, ha az axios postnál megadott név nem egyezik a módosítandó oszlop nevével,
       * akkor szükséges egy 3. paraméter is a $rules tömbbe -> 'unique:ingredients,name'
       *
       * Törekedjünk arra, hogy már axios postnál a módosítandó tábla nevét kapja az object, aminek fieldjei legyenek az oszlop nevei lesznek!
       *
       * Validátor 3 paramétert vár: input, szabályok, hibaüzenetek
       *
       */

      /*
       * Meghatározzuk a szabályokat a validátornak
       *
       * ingredients.name - ingredients az axios postolt objektum neve, és name, az egyik fieldje
       */

      $rules = [
         'ingredients.name' => ['required', 'unique:ingredients', 'min:2', 'max:20'],
         'ingredients.unit_type' => ['required', 'min:1', 'max:30'],
         'ingredients.unit_price' => ['between:1,150000', 'integer', 'required']
      ];

      /*
       * Egyedi hibaüzenetek a validációhoz amik visszamennek majd frontendre.
       */

      $message = [
         'unique' => 'A :input már szerepel!',
         'required' => 'Ne hackeld az oldalt pls, backenden is van validáció!',
         'ingredients.name.min' => 'Legalább 2 karakter hosszú nevet adj meg!',
         'ingredients.name.max' => 'Ne kisregény legyen az alapanyag neve! Max 20 karakter!',
         'ingredients.unit_price.required' => 'Ne hackeld az oldalt pls, backenden is van validáció!',
         'ingredients.unit_price.between' => ':min és :max közötti számot adj meg kérlek!',
         'ingredients.unit_price.integer' => 'Úgy látom már nem is integer típusú a számunk.. ennyire tuti, hogy nem drága az alapanyag :/',
         'ingredients.unit_type.min' => 'Legalább :min karakter hosszú legyen az egységtípus!',
         'ingredients.unit_type.max' => 'Max :max karakter lehet az egységtípus!',
      ];

      /*
       * Lefut a validátor: első paraméter az input, második a szabály, harmadik (opcionális) egyedi hibaüzenet
       */

      $validator = Validator::make($request, $rules, $message);

      /*
       * Fontos! Kézzel kell beállítani a response kódot is, mivel 200-at küldene vissza, miután hibával elszáll a backend.
       * Tehát ha itt nem írjuk oda a response kódot akkor minden esetben 200-as response codeot tol vissza a hibaüzenettel együtt.
       * Update: mivel kiszerveztük a validálást, ezért az ezt meghívó függvénybe helyeztük a 420-as kódot, ha ez a folyamat hibával térne vissza.
       */
      if( $validator->fails()) {
         return $validator->errors();
      }
      return true;
   }

   public function validateNewRecipeName($request) {
      $rules = [
         'name' => ['required', 'unique:cakes', 'min:2', 'max:30']
      ];

      $message = [
         'required' => 'Ne hackeld az oldalt pls, backenden is van validáció!',
         'name.unique' => ':input recept már létezik!',
         'name.min' => 'A név legyen minimum 2 karakter hosszú!',
         'name.max' => 'A név nem lehet hosszabb :max karakternél!',
      ];

      $validator = Validator::make($request, $rules, $message);

      if( $validator->fails()) {
         return $validator->errors();
      }
      return true;
   }

   /*
    * Validáljuk az alapanyag törlését. Ha a törlendő alapanyag hozzá van csatolva egy recepthez,
    * akkor feltöltünk egy tömböt azokkal receptek neveivel, ahol használva van a törlendő alapanyag,
    * és visszaküldjük 422-es kóddal
    *
    * Ellenkező esetben töröljük.
    */
   public function validateDeleteIngredient($request) {
      $deletableIngredient = Ingredient::find($request->removableId);

      if(sizeof($deletableIngredient->cakes) !== 0) {
         $error = ['Nem törölheted! Ez az alapanyag hozzá van rendelve a következő receptekhez:'];

         /*
          * Az alapanyaghoz csatolt receptek listáját így érjük el az Eloquent segítségével:
          * $aktuálisAlapanyag->cakes
          *
          * update: a fejlesztés előrehaladtával elkezdtem recipe kulcsszót használni cake helyett
          * Ez néhol megtévesztő jelent tehát:
          * TODO: refaktorálni a cake nevet recipere a kódban
          */
         foreach ($deletableIngredient->cakes as $recipe) {
            array_push($error, $recipe->name);
         }

         return $error;
      }
      return true;
   }

   public function validateModifyUser($request) {
      //Todo validálás
      Log::info($request);
      die();

      $rules = [
         'name' => ['required', 'unique:cakes', 'min:2', 'max:30']
      ];

      $message = [
         'required' => 'Ne hackeld az oldalt pls, backenden is van validáció!',
         'name.unique' => ':input recept már létezik!',
         'name.min' => 'A név legyen minimum 2 karakter hosszú!',
         'name.max' => 'A név nem lehet hosszabb :max karakternél!',
      ];

      $validator = Validator::make($request, $rules, $message);

      if( $validator->fails()) {
         return $validator->errors();
      }
      return true;

   }
}