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

      /*
       * Ha már meglévő alapanyagot módosítunk
       * (tehát ha az input tartalmaz ID-t)
       */

      if(isset($newIngredientData['ingredients']['id'])) {
         /*
          * Megkeressük id alapján az ingredientet, és példányosítjuk eloquenttel
          */
         $newIngredient = Ingredient::find($newIngredientData['ingredients']['id']);
      }
      else {
         /*
          * Létrehozunk egy új ingredient objektumot
          */
         $newIngredient = new Ingredient;
      }

      $newIngredient->name = $newIngredientData['ingredients']['name'];

      $newIngredient->uploaded_unit_type = $newIngredientData['ingredients']['type_name'];
      $newIngredient->uploaded_unit_price = $newIngredientData['ingredients']['unit_price'];
      $newIngredient->uploaded_unit_quantity = $newIngredientData['ingredients']['uploaded_unit_quantity'];
      $newIngredient->unit_category = $newIngredientData['ingredients']['unit_category'];


      /*
       * Kiszámoljuk az alapanyag egységégárát (a unit kategórián belüli legkisebb mértékegységre)
       * Tehát ha tömeg típusú az alapanyag akkor gramm, ha térfogat, akkor ml
       */

      $newIngredient->unit_price = $this->calculateIngredientUnitPriceByInput($newIngredientData);


      if(isset($newIngredientData['ingredients']['id'])) {
         $this->updateIngredientSumPricesInExistingCakes($newIngredient);
      }

      $newIngredient->save();
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
      /*
       * Deklaráljuk az átváltási számot
       */

      /*
       * A unit_id az azonosító, ami alapján megkeressük a váltószámot, ami a mértékegységhez
       * tartozik. Frontenden ha kiválasztjuk a select mezőből a unit_typeot, akkor feltöltésnél az ID-ja is postolódik.
       *
       * A váltószámmal minden esetben az egységtípus legkisebb mértékegységére történő váltást lehet kszámolni!
       */

      $unit_conversion_rate = Unit::find($newIngredientData['ingredients']['unit_id'])->conversion_rate;

      /*
       * Átváltjuk a feltöltött mennyiséget az adott alapanyag egységtípusának legkisebb mértékegységére.
       */
      //TODO: nullával osztást kivédeni!
      $ingredientQuantityInSmallestUnitOfItsCategory = $newIngredientData['ingredients']['uploaded_unit_quantity'] * $unit_conversion_rate;
      /*
       * Visszaküldjük double értékben az alapanyag típusa szerinti, legkisebb mértékegységgel számolt egységárát.
       */
      return (double)($newIngredientData['ingredients']['unit_price'] / (double)$ingredientQuantityInSmallestUnitOfItsCategory);
   }
}
