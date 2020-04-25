<?php


namespace App\Http\Services;
use App\Cake;
use App\Ingredient;
use Illuminate\Support\Facades\Log;

class IngredientService
{
   public function saveNewIngredient($newIngredientData) {

      $newIngredient = new Ingredient;
      $newIngredient->name = $newIngredientData['ingredients']['name'];
      $newIngredient->unit_type = $newIngredientData['ingredients']['unit_type'];
      $newIngredient->unit_price = $newIngredientData['ingredients']['unit_price'];
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
      }

   }
}
