<?php


namespace App\Http\Services;

use App\Cake;
use Illuminate\Support\Facades\DB;

class CakeService
{
/*    public function generateSumIngredientsPrice(Cake $cake) {
        $cake->setingredients_price_sum(DB::table('required_ingredients')->where('cake_id', $this->cake->id)->sum('ingredient_price'));
    }*/

    public function generateSumIngredientsPriceForAllCakes($cakes) {

        foreach ($cakes as $cake) {

//          $cake->ingredients_price_sum = DB::table('required_ingredients')->where('cake_id', $cake->id)->sum('ingredient_price');

            $cake->ingredients_price_sum = $cake->required_ingredients()->where('cake_id', $cake->id)->sum('ingredient_price');

            $cake->save();
        }
    }

    public function validateNewCakeNameForRegister($recipeName) {
       if(!$this->checkInputLength($recipeName)) {
          return 406;
       }
       else if(!$this->checkIfRecipeNameAlreadyExists($recipeName)) {
          return 409;
       }

       return 202;
    }

    public function registerRecipeToDb($recipeName) {
       $recipeName = strtolower($recipeName);

       $newCake = new Cake;
       $newCake->name = $recipeName;
       $newCake->save();
    }

    private function checkInputLength($recipeName) {
       if(strlen($recipeName) > 3) {
          return true;
       }
       return false;
    }

    private function checkIfRecipeNameAlreadyExists($recipeName) {
       $recipeName = strtolower($recipeName);

       if(Cake::where('name', '=', $recipeName)->first() === null) {
          return true;
       }
       return false;
    }
}
