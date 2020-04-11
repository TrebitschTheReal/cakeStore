<?php


namespace App\Http\Services;
use App\Ingredient;

class IngredientService
{
   public function saveNewIngredient($newIngredientData) {

      $newIngredient = new Ingredient;
      $newIngredient->name = $newIngredientData['newIngredient']['name'];
      $newIngredient->unit_type = $newIngredientData['newIngredient']['unitType'];
      $newIngredient->unit_price = $newIngredientData['newIngredient']['unitPrice'];
      $newIngredient->save();
   }
}
