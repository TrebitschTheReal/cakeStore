<?php

namespace App\Http\Controllers;

use App\Http\Services\CakeService;
use Illuminate\Http\Request;
use App\Cake;
use Illuminate\Support\Facades\DB;

class CakeController extends Controller
{
   public function index()
   {
      return view('cakes/cakeRecipeList');
   }

   public function createRecipeView()
   {
      return view('cakes/createNewRecipe');
   }

   public function getAllCakeRecipes()
   {

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
   }

   //Ide érkezik be a request a recept feltöltésekor
   public function fillNewlyCreatedRecipe(Request $request)
   {
      //Átalakítjuk a request jsont egy asszociatív tömbbé.
      $newRecipeContent = $request->all();

      $cakeService = new CakeService();
      $cakeService->fillNewlyRegisteredRecipe($newRecipeContent);

   }
}
