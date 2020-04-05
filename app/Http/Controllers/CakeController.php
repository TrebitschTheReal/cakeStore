<?php

namespace App\Http\Controllers;

use App\Http\Services\CakeService;
use Illuminate\Http\Request;
use App\Cake;
use Illuminate\Support\Facades\DB;

class CakeController extends Controller
{
    public function index() {
        return view('cakes/cakeRecipeList');
    }

    public function createRecipeView() {
       return view('cakes/createNewRecipe');
    }

    public function getAllCakeRecipes() {

        //TODO ################ RECEPT FELTÖLTÉS -> létrehozni a feltöltés funkciót  #############################


        //TODO ################ RECEPT FELTÖLTÉS -> létrehozni a feltöltés funkciót  #############################


        $cakes = Cake::with('required_ingredients')->get();
        $cakeService = new CakeService();
        $cakeService->generateSumIngredientsPriceForAllCakes($cakes);


        return $cakes;

        //Elvileg ez ugyan ezt csinálja, csak a Laravel stringifyolja már az elején.
        //return response()->json($cakes);
    }

   public function registerNewRecipe(Request $request)
   {
      $cakeService = new CakeService();
      $responseStatusCode = $cakeService->validateNewCakeNameForRegister($request->recipeName);

      if ($responseStatusCode === 202) {
         $newRecipe = $cakeService->registerRecipeToDb($request->recipeName);

         return response()->json(array(
            'success' => true,
            'new_recipe_id' => $newRecipe->id,
            'new_recipe_name' => $newRecipe->name

         ), 200);

      } else {
         return response('Hiba a validálás során', $responseStatusCode);
      }




/*     $newCake = new Cake;
       $newCake->name = 'Tordasi Kencefice';
       $newCake->desc = 'Tordas sava borsa';

       //először el kell menteni, hogy generálódjon neki egy ID
       $newCake->save();

       //Jöhet az alapanyagok hozzárendelése
       $newCake->required_ingredients()->attach(4, array('ingredient_quantity' => 7, 'ingredient_price' => 87));
       $newCake->required_ingredients()->attach(2, array('ingredient_quantity' => 5, 'ingredient_price' => 34));
       $newCake->required_ingredients()->attach(1, array('ingredient_quantity' => 23, 'ingredient_price' => 843));
       $newCake->required_ingredients()->attach(3, array('ingredient_quantity' => 2, 'ingredient_price' => 435));

       //Az alapanyagok hozzárendelését követően kiszámoljuk, hogy mennyi az anyagköltség a tortához rendelt alapanyagok részenként sumjaiból
       $newCake->ingredients_price_sum = $newCake->required_ingredients()->where('cake_id', $newCake->id)->sum('ingredient_price');*/
    }

    public function fillNewlyCreatedRecipe(Request $request) {
       $newRecipeContent = $request->all();
       $newRecipeId = $newRecipeContent['newRecipe']['id'];

       $newRecipeDesc = $newRecipeContent['newRecipe']['desc'];

       $newRecipe = Cake::find($newRecipeId);
       $newRecipe->desc = $newRecipeDesc;

       foreach ($newRecipeContent['newRecipe']['ingredients'] as $newRecipeIngredient => $ingredientValue) {
          $ingredientID = $ingredientValue['id'];
          $ingredientName = $ingredientValue['name'];
          $ingredientQuantity = $ingredientValue['quantity'];
          $ingredientSumPrice = $ingredientValue['sumIngredientPrice'];

          $newRecipe->required_ingredients()->attach($ingredientID, array('ingredient_quantity' => $ingredientQuantity, 'ingredient_price' => $ingredientSumPrice));
       }

    }
}
