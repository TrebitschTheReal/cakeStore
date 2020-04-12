<?php

namespace App\Http\Controllers;

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

      //TODO: nem működik ez a szar, mindent átenged 200-al
      $validator = Validator::make($request->all(), [
         'FOOOO.name' => 'unique:ingredients'
      ]);

      if( $validator->fails() ) {
         return $validator->errors();
      }

      $ingredientService = new IngredientService;
      $ingredientService->saveNewIngredient($request);
   }
}
