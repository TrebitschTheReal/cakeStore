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
}
