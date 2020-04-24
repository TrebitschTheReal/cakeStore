<?php


namespace App\Http\Services;


use Illuminate\Support\Facades\Validator;

class ValidatorService
{
   public function validateAlreadyExistsIngredient($request) {
      $rules = [
         'ingredients.name' => ['required', 'min:2', 'max:20'],
         'ingredients.unit_price' => ['between:1,150000', 'integer', 'required']
      ];

      $message = [
         'ingredients.unit_price.required' => 'Ne hackeld az oldalt pls, backenden is van validáció!',
         'ingredients.unit_price.between' => '1 és 150.000 közötti számot adj meg kérlek!',
         'ingredients.unit_price.integer' => 'Úgy látom már nem is integer típusú a számunk.. ennyire tuti, hogy nem drága az alapanyag :/',
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
         'ingredients.unit_price.between' => '1 és 150.000 közötti számot adj meg kérlek!',
         'ingredients.unit_price.integer' => 'Úgy látom már nem is integer típusú a számunk.. ennyire tuti, hogy nem drága az alapanyag :/',

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
         'name' => ['min:2', 'max:30', 'required', 'unique:cakes']
      ];

      $message = [
         'name.min' => 'A név legyen minimum 2 karakter hosszú!',
         'name.max' => 'A név nem lehet hosszabb :max karakternél!',
         'name.unique' => ':input recept már létezik!',
         'required' => 'Ne hackeld az oldalt pls, backenden is van validáció!',
      ];

      $validator = Validator::make($request, $rules, $message);

      if( $validator->fails()) {
         return $validator->errors();
      }
      return true;
   }
}