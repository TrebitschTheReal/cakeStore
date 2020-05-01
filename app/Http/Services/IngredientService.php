<?php


namespace App\Http\Services;
use App\Cake;
use App\Ingredient;
use App\Unit;
use Illuminate\Support\Facades\Log;
use League\Flysystem\Adapter\Ftp;

class IngredientService
{
   public function saveNewIngredient($newIngredientData) {
      $newIngredient = new Ingredient;
      $newIngredient->name = $newIngredientData['ingredients']['name'];
      $newIngredient->uploaded_unit_type = $this->getUploadableIngredientUnitType($newIngredientData);
      $newIngredient->uploaded_unit_price = $newIngredientData['ingredients']['unit_price'];

      foreach ($newIngredientData['ingredients']['unit_type'] as $key => $value){
         if($key == 'unit_category') {
            $newIngredient->unit_category = $value;
         }
      }

      $newIngredient->unit_price = $this->calculateIngredientUnitPriceByInput($newIngredientData);
      $newIngredient->save();
   }

   public function updateIngredient($newIngredientData) {

      $ingredient = Ingredient::find($newIngredientData['ingredients']['id']);
      $ingredient->unit_price = $newIngredientData['ingredients']['unit_price'];
      $ingredient->save();

      $this->updateIngredientSumPricesInExistingCakes($ingredient);
   }

   /*
    * Frissítjük az adott recepthez csatolt receptek alapanyagonkénti sum költségeit.
    */
   public function updateIngredientSumPricesInExistingCakes($ingredient) {

      /*
       * Végigiterálunk a megkapott alapanyaghoz rendelt recepteken
       * Eloquent: $ingredient->cakes    -  visszaadja azokat a recepteket ahol az adott alapanyag csatolva van
       */
      foreach ($ingredient->cakes as $cake) {
         $newPrice = $ingredient->unit_price * $cake->pivot->ingredient_quantity;
         /*
          * Frissítjük az adott cake objektum (tehát az alapanyagokhoz rendelt receptek aktuális objektumának)
          * adott alapanyagra vonatkozó sum értékét (alapanyag mennyisége * új árral)
          */
         $ingredient->cakes()->updateExistingPivot($cake->id, ['ingredient_price' => $newPrice]);

         /*
          * Miután frissítettük a pivot táblát, létre kell hoznunk egy Cake objektumot,
          * ahol már a frissített pivot tábla értékeivel SUM értéket számolunk az adott recept anyagköltségére:
          */
         $newCake = Cake::find($cake->id);
         $newCake->ingredients_price_sum = $cake->
                                          required_ingredients()->
                                          where('cake_id', $cake->id)->
                                          sum('ingredient_price');

         /*
          * Elmentjük a módosított tortát, ezt követően a következő ciklusba ugrunk
          */
         $newCake->save();

         //TODO: validálni a sum értékeket, hogy ne legyen túlcsordulás
      }

   }

   public function calculateIngredientUnitPriceByInput($newIngredientData) {
      $unit_conversion_rate = 0;

      foreach ($newIngredientData['ingredients']['unit_type'] as $key => $value){
         if($key == 'id') {
            $unit_conversion_rate = Unit::find($value)->conversion_rate;
         }
      }

      $ingredientQuantityInSmallestUnitOfItsCategory = $newIngredientData['ingredients']['quantity'] * $unit_conversion_rate;

      return (double)($newIngredientData['ingredients']['unit_price'] / (double)$ingredientQuantityInSmallestUnitOfItsCategory);
   }

   public function getUploadableIngredientUnitType($newIngredientData) {
      foreach ($newIngredientData['ingredients']['unit_type'] as $key => $value){
         if($key == 'type_name') {
            return $value;
         }
      }
   }
}
