<?php

namespace App\Http\Controllers;

use App\Http\Services\CakeService;
use App\Http\Services\ValidatorService;
use Illuminate\Http\Request;
use App\Cake;
use Illuminate\Support\Facades\Log;

class CakeController extends Controller
{
   public function index()
   {
      return view('recipes/cakeRecipeList');
   }

   public function createRecipeView()
   {
      return view('recipes/operationsRecipe');
   }

   public function getAllCakeRecipes()
   {
      $cakes = Cake::with('required_ingredients')->get();
      return response()->json($cakes);
   }


   /*
    * Új recept regisztrálásánál ide fut be az ajax post.
    * Lefut a laravel validátora, és ha sikeresen átment rajta, akkor meghívódik a CakeService,
    * ahol regisztrálódik a recept neve az adatbázisban.
    * Ha a regisztráció sikeres, akkor a CakeService visszaadja az új recept id-ját, nevét, majd visszaküldi egy 200-as response kóddal frontendre.
    */
   public function registerNewRecipe(Request $request)
   {
      $input = $request->all();
      Log::info(print_r($input, true));
      $validatorService = new ValidatorService();
      $response = $validatorService->validateNewRecipeName($input);

      if($response !== true) {
         return response($response, 422);
      }


      $cakeService = new CakeService();

      $newRecipe = $cakeService->registerRecipeToDb($request->name);

      return response()->json(array(
         'success' => true,
         'new_recipe_id' => $newRecipe->id,
         'new_recipe_name' => $newRecipe->name

      ), 200);

   }

   public function deleteRecipe(Request $request) {
      $removableCake = Cake::find($request->removableId);
      $removableCake->required_ingredients()->detach();
      $removableCake->delete();
   }

   /*
    * Ide érkezik be a request a recept feltöltésekor
    */
   public function fillNewlyCreatedRecipe(Request $request)
   {
      //Átalakítjuk a request jsont egy asszociatív tömbbé.
      $newRecipeContent = $request->all();

      $validatorService = new ValidatorService();

      foreach ($newRecipeContent['newRecipe']['ingredients'] as $key => $value){
         $ingredients['ingredients'] = $value ;
         $response = $validatorService->validateIngredient($ingredients);

         if($response !== true) {
            return response($response, 422);
         }
      }

      $validatorService = new ValidatorService();
      $response = $validatorService->validateRecipeName($newRecipeContent);

      if($response !== true) {
         return response($response, 422);
      }

      $cakeService = new CakeService();
      $cakeService->fillNewlyRegisteredRecipe($newRecipeContent);

   }

   public function getmodifiableRecipe(Request $request) {
      $modifiableCake = Cake::with('required_ingredients')->find($request->modifiableRecipeId);
      return response($modifiableCake);
   }
}
