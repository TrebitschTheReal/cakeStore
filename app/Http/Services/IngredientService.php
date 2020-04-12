<?php


namespace App\Http\Services;
use App\Cake;
use App\Ingredient;
use Illuminate\Support\Facades\Log;

class IngredientService
{
   public function saveNewIngredient($newIngredientData) {

      $newIngredient = new Ingredient;
      $newIngredient->name = $newIngredientData['newIngredient']['name'];
      $newIngredient->unit_type = $newIngredientData['newIngredient']['unitType'];
      $newIngredient->unit_price = $newIngredientData['newIngredient']['unitPrice'];
      $newIngredient->save();
   }

   public function updateIngredient($newIngredientData) {

      $ingredient = Ingredient::find($newIngredientData['ingredient']['id']);
      $ingredient->unit_price = $newIngredientData['ingredient']['unitPrice'];
      $ingredient->save();

      $this->updateIngredientSumPricesInExistingCakes();

   }

   public function updateIngredientSumPricesInExistingCakes() {
      Log::info('------------------------------------------------');
      $cakes = Cake::with('required_ingredients')->get();

      //TODO: itt valami gubanc van. Nem mentődik el DB-ben az adat
      foreach ($cakes as $cake) {
         foreach ($cake->required_ingredients as $ingredient) {
            $ingredient['pivot']->ingredient_price = $ingredient->unit_price * $ingredient['pivot']->ingredient_quantity;
         }
         $cake->ingredients_price_sum = $cake->required_ingredients()->sum('ingredient_price');
         $cake->save();
      }



      foreach ($cakes as $cake) {
         foreach ($cake->required_ingredients as $ingredient){
            $ingredient['pivot']->ingredient_price = $ingredient->unit_price * $ingredient['pivot']->ingredient_quantity;
            break;
         }
         echo($cake['required_ingredients'][0]['pivot']);
         $cake->save();
         break;
      }
   }
}
