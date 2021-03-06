<?php


namespace App\Http\Services;

use App\Cake;
use App\Unit;
use Illuminate\Support\Facades\Log;

class CakeService
{
   public function generateSumIngredientsPriceForAllCakes($cakes)
   {

      foreach ($cakes as $cake) {

         //Laravel - DB - összesíti az adott tortához csatolt ingredientek árát - anyagköltség
         //$cake->ingredients_price_sum = DB::table('required_ingredients')->where('cake_id', $cake->id)->sum('ingredient_price');

         //Laravel - Eloquent - összesíti az adott tortához csatolt ingredientek árát - anyagköltség
         $cake->ingredients_price_sum = $cake->required_ingredients()->where('cake_id', $cake->id)->sum('ingredient_price');

         //Frissítjük az updated_at oszlopot
         $cake->touch();

         //Elmentjük a modellt
         $cake->save();
      }
   }

   /*
    * Létrehozunk az adatbázisban egy új tortát egy új névvel, majd elmentjük, hogy kapjon ID-t.
    */
   public function registerRecipeToDb($recipeName)
   {
      $recipeName = strtolower($recipeName);

      $newRecipe = new Cake;
      $newRecipe->name = $recipeName;

      $newRecipe->save();

      return $newRecipe;
   }

   /*
   A függvény megkapja az új tortarecept lényegében minden adatát, és a hozzá rendelendő alapanyagok adatait is asszociatív tömbbként.
   */
   public function fillNewlyRegisteredRecipe($newRecipeContent)
   {
      $newRecipeId = $newRecipeContent['newRecipe']['id'];
      $newRecipeDesc = $newRecipeContent['newRecipe']['desc'];

      //Validálásra vár
      //$newRecipeName = $newRecipeContent['newRecipe']['name'];

      //Megkeressük a nemrég regisztrált torta receptet az id alapján
      $newRecipe = Cake::find($newRecipeId);
      $newRecipe->name = $newRecipeContent['newRecipe']['name'];
      $newRecipe->desc = $newRecipeDesc;

      //Validálásra vár
      //$newRecipe->name = $newRecipeName;

      //Lekötjük az ÖSSZES hozzárendelt alapanyagot - ha módosítunk, akkor úgy is feltölti újra az egészet
      //ha új a torta, akkor pedig eleve nincs még attacholva semmi

      $newRecipe->required_ingredients()->detach();

      //Végigiterálunk az asszociatív tömb 'ingredients' ágán
      foreach ($newRecipeContent['newRecipe']['ingredients'] as $newRecipeIngredient => $ingredientValue) {
         $ingredientID = $ingredientValue['id'];
         $ingredientQuantity = $ingredientValue['quantity'];
         $ingredientUniTypeName = $ingredientValue['type_name'];
         $ingredientPrice = (double)$this->convertToSmallestUnit($ingredientValue) * (double)$ingredientValue['unitPrice'];

         //Laravel - Eloquent - hozzárendeljük az alapanyagot (illetve a mennyiséget és az adott alapanyag sum árát) a tortához
         $newRecipe->required_ingredients()->attach($ingredientID, array (
            'ingredient_quantity' => $ingredientQuantity,
            'ingredient_unit_type' => $ingredientUniTypeName,
            'ingredient_price' => $ingredientPrice,
         ));
      }

      //Laravel - Eloquent - összegezzük az alapanyagok költségét - anyagköltség
      $newRecipe->ingredients_price_sum = $newRecipe->required_ingredients()->where('cake_id', $newRecipe->id)->sum('ingredient_price');

      //Frissítjük az updated_at oszlopot
      $newRecipe->touch();

      //elmentjük a torta receptet
      $newRecipe->save();
   }

   public function convertToSmallestUnit($ingredient) {
      $unit_conversion_rate = Unit::find($ingredient['unit_id'])->conversion_rate;
      return (double)$ingredient['quantity'] * $unit_conversion_rate;
   }
}
