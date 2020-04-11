<?php

namespace App\Http\Controllers;

use App\Http\Services\IngredientService;
use Illuminate\Http\Request;
use App\Ingredient;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
   public function fetchIngredients() {
      $ingredients = Ingredient::all();
      return response($ingredients);
   }

   public function registerNewIngredient(Request $request) {

      //TODO: nem működik ez a szar, mindent átenged 200-al
      Validator::make($request->all(), [
         'newIngredient.name' => 'required|unique:ingredients|min:3|max:40',
      ]);

      $ingredientService = new IngredientService;
      $ingredientService->saveNewIngredient($request);
   }
}
