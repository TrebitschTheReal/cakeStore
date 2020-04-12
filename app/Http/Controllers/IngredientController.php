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
       * Ha object érkezik a Laravel validátorába, akkor azt a Validator osztály képes kezelni.
       * Először asszociatív tömbbé alakítja a requestet, majd - mintha JavaScriptben lennénk - ponttal (.)
       * tudunk hivatkozni az objektumunk egyik mezőjére:  'newIngredient.name'
       * Fontos!: itt mindent manuálisan kell megadnunk! Tehát a validálás értékének kell egy harmadik paraméter is, ami
       * az adatbázis oszlopának nevére hivatkozik -> 'unique:ingredients,name'
       *
       * Törekedjünk arra, hogy a frontendről axios postnál ugyan azt a nevet kapja az objektum, amilyen oszlopba fel fogjuk tölteni:
       *
       *  $validator = Validator::make($request->all(), [
            'ingredients.name' => 'unique:ingredients'
          ]);
       */

      $validator = Validator::make($request->all(), [
         'newIngredient.name' => 'unique:ingredients,name'
      ]);

      /*
       * Fontos! Kézzel kell beállítani a response kódot is, mivel 200-at küldene vissza, miután hibával elszáll a backend.
       * Tehát ha itt nem írjuk oda a response kódot akkor minden esetben 200-at lök vissza a hibaüzenettel együtt.
       */
      if( $validator->fails()) {
         return response($validator->errors(), 422);
      }

      $ingredientService = new IngredientService;
      $ingredientService->saveNewIngredient($request);
   }

   public function modifyExistingIngredient(Request $request) {
      $newIngredientData = $request->all();
      $ingredientService = new IngredientService;
      $ingredientService->updateIngredient($newIngredientData);

   }
}
