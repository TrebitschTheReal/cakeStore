<?php


namespace App\Http\Services;


use App\Ingredient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ValidatorService
{
   public function validateIngredient($request) {
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

      /*
       * Laravel in:
       * 'ingredients.type_name' => ['required', 'in:ml,cl,dl,l,g,dkg,kg,db']
       *
       *  Laravel regex:
       * 'ingredients.type_name' => ['required', 'regex:(foo|bar|baz)']
       *
       * Exists:
       * 'ingredients.type_name' => ['required', 'exists:units']
       */

      /*
       * Léterhozunk egy rules tömböt, majd beállítjuk a szabályokat.
       * listID: frontenden van rá szükség az összeállítás rendezéséhez.
       * Ha a recepetet módosítjuk, mindenképp postolódik.
       */

      $rules = [];

      /*
       * Új alapanyag felvétele (se id, se listId)
       */
      if (!isset($request['ingredients']['id']) && !isset($request['ingredients']['listID'])) {
         $rules = [
            'ingredients.name' => ['required', 'unique:ingredients', 'between:1,50', 'string'],
            'ingredients.uploaded_unit_quantity' => ['required', 'between:1,5000',  'integer'],
            'ingredients.type_name' => ['required', 'exists:units,type_name'],
            'ingredients.unit_price' => ['required', 'between:1,150000', 'integer'],
         ];
      }

      /*
       * Meglévő alapanyag módosítása (van id, de nincs list Id)
       */
      else if (isset($request['ingredients']['id']) && !isset($request['ingredients']['listID'])) {
         $rules = [
            'ingredients.name' => ['required', 'between:1,50', 'string'],
            'ingredients.uploaded_unit_quantity' => ['required', 'between:1,5000',  'integer'],
            'ingredients.type_name' => ['required', 'exists:units,type_name'],
            'ingredients.unit_price' => ['required', 'between:1,150000', 'integer'],
         ];
      }

      /*
       * Recept alapanyag (van id, van listID)
       */
      else if (isset($request['ingredients']['id']) && isset($request['ingredients']['listID'])) {
         $rules = [
            'ingredients.name' => ['required', 'between:1,50', 'string', 'exists:ingredients'],
            'ingredients.type_name' => ['required', 'exists:units,type_name'],
            'ingredients.quantity' => ['required', 'between:1,5000', 'integer']
         ];
      }

      /*
       * Egyedi hibaüzenetek a validációhoz amik visszamennek majd frontendre.
       */

      $message = [
         'unique' => 'A :input már szerepel!',
         'integer' => 'A megadott érték (már) nem integer típusú. Vagy nagyon nagy számot adtál meg, vagy ügyeskedni próbálsz.',

         'ingredients.name.required' => 'Muszáj nevet megadnod!',
         'ingredients.name.between' => 'Az alapanyag neve minimum :min és maximum :max karakter hosszú lehet!',
         'ingredients.name.exists' => 'Csak a megadott alapanyagokból választhatsz!',

         'ingredients.unit_price.required' => 'Muszáj árat megadnod!',
         'ingredients.unit_price.between' => 'Az ár :min és :max közötti érték kell, hogy legyen!',

         'ingredients.type_name.required' => 'Muszáj egységtípust megadnod!',
         'ingredients.type_name.exists' => 'Csak a megadott egységtípusok közül választhatsz!',

         'ingredients.uploaded_unit_quantity.required' => 'Muszáj mennyiséget megadnod!',
         'ingredients.uploaded_unit_quantity.between' => 'A mennyiség :min és :max közötti érték kell, hogy legyen!',

         'ingredients.quantity.required' => 'Muszáj mennyiséget megadnod!',
         'ingredients.quantity.between' => 'A mennyiség :min és :max közötti érték kell, hogy legyen!',
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
         'required' => 'Muszáj nevet megadnod!',
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

   public function validateRecipeName($request) {
      $rules = [
         'newRecipe.name' => ['required', 'between:1,50', 'string'],
      ];


      $message = [
         'newRecipe.name.required' => 'Muszáj megadnod nevet!',
         'newRecipe.name.between' => 'Az recept neve minimum :min és maximum :max karakter hosszú lehet!',
      ];

      $validator = Validator::make($request, $rules, $message);

      if( $validator->fails()) {
         return $validator->errors();
      }
      return true;
   }

   public function validateUser($request) {
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
      Log::info($request);

      $rules = [
         'user.name' => ['required', 'between:1,50', 'string'],
         'user.email' => ['required', !isset($request['user']['id']) ? 'unique:users,email' : '', 'between:1,50',  'string'],
         'user.role_name' => ['required', 'between:1,50', 'string'],
      ];

      /*
       * Egyedi hibaüzenetek a validációhoz amik visszamennek majd frontendre.
       */

      $message = [
         'unique' => ':input e-mail cím már regisztrált!',

         'user.name.required' => 'Muszáj megadnod nevet!',
         'user.name.between' => 'A felhasználó neve minimum :min és maximum :max karakter hosszú lehet!',

         'user.email.required' => 'Muszáj megadnod e-mail címet!',
         'user.email.between' => 'Az email minimum :min és maximum :max karakter hosszú lehet!',

         'user.role_name.required' => 'Muszáj megadnod a jogosultsági szintet!',
         'user.role_name.between' => 'A jogosultsági szint minimum :min és maximum :max karakter hosszú lehet!',
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
}