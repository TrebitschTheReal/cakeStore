<?php

namespace App\Http\Controllers;

use App\Http\Services\IngredientService;
use App\Http\Services\ValidatorService;
use Illuminate\Http\Request;
use App\Ingredient;

class IngredientController extends Controller
{
   public function fetchIngredients() {
      $ingredients = Ingredient::all();
      return response($ingredients);
   }

   public function registerNewIngredient(Request $request) {
      $input = $request->all();

      /*
       * Validáljuk a beérkező inputot, erre a saját validatorService osztályunkat használjuk
       * (ami pedig a laravel 'out of the box' validátorát használja, de így még jobban el tudjuk különíteni a folyamatokat)
       */
      $validatorService = new ValidatorService();
      $response = $validatorService->validateNewIngredient($input);

      if($response !== true) {
         return response($response, 422);
      }

      /*
       * Ha az input megfelelő (tehát nem dobtunk vissza 422-őt) akkor tovább fut a controller, és meghívja a service osztályt
       * az input feltöltéséhez.
       */
      $ingredientService = new IngredientService;
      $ingredientService->saveNewIngredient($request);

   }

   public function modifyExistingIngredient(Request $request) {
      $input = $request->all();
      $validatorService = new ValidatorService();
      $response = $validatorService->validateAlreadyExistsIngredient($input);

      if($response !== true) {
         return response($response, 422);
      }

      $ingredientService = new IngredientService;
      $ingredientService->updateIngredient($input);

   }

   public function deleteIngredient(Request $request) {

      $validatorService = new ValidatorService();
      $response = $validatorService->validateDeleteIngredient($request);

      if($response !== true) {
         return response($response, 422);
      }

      $removableIngredient = Ingredient::find($request->removableId);
      $removableIngredient->delete();
   }
}
