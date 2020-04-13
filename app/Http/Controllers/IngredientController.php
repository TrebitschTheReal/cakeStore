<?php

namespace App\Http\Controllers;

use App\Http\Services\CakeService;
use App\Http\Services\IngredientService;
use Illuminate\Http\Request;
use App\Ingredient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
   public function fetchIngredients() {
      $ingredients = Ingredient::all();
      return response($ingredients);
   }

   public function registerNewIngredient(Request $request) {

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

      $rules = [
         'ingredients.name' => ['required', 'unique:ingredients', 'min:2', 'max:20'],
      ];

      $message = [
         'unique' => 'A :input már szerepel!',
         'required' => 'Ne hackeld az oldalt pls, backenden is van validáció!',
         'ingredients.name.min' => 'Legalább 2 karakter hosszú nevet adj meg!',
         'max' => 'Ne kisregény legyen az alapanyag neve! Max 20 karakter!'

      ];

      $validator = Validator::make($request->all(), $rules, $message);

      /*
       * Fontos! Kézzel kell beállítani a response kódot is, mivel 200-at küldene vissza, miután hibával elszáll a backend.
       * Tehát ha itt nem írjuk oda a response kódot akkor minden esetben 200-as response codeot tol vissza a hibaüzenettel együtt.
       */
      if( $validator->fails()) {
         return response($validator->errors(), 422);
      }

      $ingredientService = new IngredientService;
      $ingredientService->saveNewIngredient($request);
   }

   public function validateNewIngredientUploadInput(Request $request) {

   }

   public function modifyExistingIngredient(Request $request) {
      $newIngredientData = $request->all();
      $ingredientService = new IngredientService;
      $ingredientService->updateIngredient($newIngredientData);

   }
}
