<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingredient;

class IngredientController extends Controller
{
   public function fetchIngredients() {
      $ingredients = Ingredient::all();
      return response($ingredients);
   }
}
