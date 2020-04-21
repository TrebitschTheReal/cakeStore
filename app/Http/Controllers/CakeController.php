<?php

namespace App\Http\Controllers;

use App\Http\Services\CakeService;
use Illuminate\Http\Request;
use App\Cake;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
      $cakeService = new CakeService();

      /*
       * Laravel validátor. Ha sikerül, továbbmegy. Ha elbukik, akkor response 422-es kóddal a validációs hibaüzenettel.
       */
/*      $request->validate([
         'name' => ['required', 'unique:cakes', 'min:3', 'max:40'],
      ]);*/

      $rules = [
         'name' => ['min:2', 'max:50', 'required', 'unique:cakes']
      ];

      $message = [
         'name.min' => 'A név legyen minimum 2 karakter hosszú!',
         'name.max' => 'A név nem lehet hosszabb :max karakternél!',
         'name.unique' => ':input recept már létezik!',
         'required' => 'Ne hackeld az oldalt pls, backenden is van validáció!',
      ];

      $validator = Validator::make($request->all(), $rules, $message);

      if( $validator->fails()) {
         return response($validator->errors(), 422);
      }

      $newRecipe = $cakeService->registerRecipeToDb($request->name);

      return response()->json(array(
         'success' => true,
         'new_recipe_id' => $newRecipe->id,
         'new_recipe_name' => $newRecipe->name

      ), 200);

   }

   /*
    * Ide érkezik be a request a recept feltöltésekor
    */
   public function fillNewlyCreatedRecipe(Request $request)
   {
      //Átalakítjuk a request jsont egy asszociatív tömbbé.
      $newRecipeContent = $request->all();

      $cakeService = new CakeService();
      $cakeService->fillNewlyRegisteredRecipe($newRecipeContent);

   }

   public function modifyRecipe(Request $request) {
      $modifiableCake = Cake::with('required_ingredients')->find($request->modifiableRecipeId);

      return response($modifiableCake);
   }
}
