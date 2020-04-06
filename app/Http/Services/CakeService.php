<?php


namespace App\Http\Services;

use App\Cake;
use Illuminate\Support\Facades\DB;

class CakeService
{
    /*    public function generateSumIngredientsPrice(Cake $cake) {
            $cake->setingredients_price_sum(DB::table('required_ingredients')->where('cake_id', $this->cake->id)->sum('ingredient_price'));
        }*/

    public function generateSumIngredientsPriceForAllCakes($cakes)
    {

        foreach ($cakes as $cake) {

//          $cake->ingredients_price_sum = DB::table('required_ingredients')->where('cake_id', $cake->id)->sum('ingredient_price');

            $cake->ingredients_price_sum = $cake->required_ingredients()->where('cake_id', $cake->id)->sum('ingredient_price');

            $cake->save();
        }
    }

    /*
     * Új recept regisztrálásánál ide fut be az ajax post. Az alábbi függvényben meghívódik a CakeService,
     * ahol a validáció is megtalálható private függvényekként, és siker esetén elmentődik a recept neve.
     *
     * Ha a mentés sikeres, akkor a CakeService visszaadja az új recept id-ját, nevét, majd visszaküldi egy 200-as response kóddal frontendre.
     * Ha a mentés sikertelen, a CakeService visszaadja az erre beállított response kódot és visszaküldi frontendre.
     */
    public function validateNewCakeNameForRegister($recipeName)
    {
        if (!$this->checkInputLength($recipeName)) {
            return 406;
        } else if (!$this->checkIfRecipeNameAlreadyExists($recipeName)) {
            return 409;
        }

        return 202;
    }

    //Létrehozunk egy új tortát egy új névvel (név nem lehet null), majd elmentjük, hogy kapjon ID-t
    public function registerRecipeToDb($recipeName)
    {
        $recipeName = strtolower($recipeName);

        $newRecipe = new Cake;
        $newRecipe->name = $recipeName;

        $newRecipe->save();

        return $newRecipe;
    }

    /*
    A függvény megkapja az új tortarecept lényegében minden adatát, és a hozzá rendelendő alapanyagok adatait is asszociatív tömbbként.
    */
    public function fillNewlyRegisteredRecipe($newRecipeContent)
    {
        $newRecipeId = $newRecipeContent['newRecipe']['id'];
        $newRecipeDesc = $newRecipeContent['newRecipe']['desc'];

        //Megkeressük a nemrég regisztrált torta receptet az id alapján
        $newRecipe = Cake::find($newRecipeId);
        $newRecipe->desc = $newRecipeDesc;

        //Végigiterálunk az asszociatív tömb 'ingredients' ágán
        foreach ($newRecipeContent['newRecipe']['ingredients'] as $newRecipeIngredient => $ingredientValue) {
            $ingredientID = $ingredientValue['id'];
            $ingredientQuantity = $ingredientValue['quantity'];
            $ingredientSumPrice = $ingredientValue['sumIngredientPrice'];

            $newRecipe->required_ingredients()->attach($ingredientID, array('ingredient_quantity' => $ingredientQuantity, 'ingredient_price' => $ingredientSumPrice));
        }

        //elmentjük a torta receptet
        $newRecipe->save();
    }

    private function checkInputLength($recipeName)
    {
        if (strlen($recipeName) > 3) {
            return true;
        }
        return false;
    }

    private function checkIfRecipeNameAlreadyExists($recipeName)
    {
        $recipeName = strtolower($recipeName);

        if (Cake::where('name', '=', $recipeName)->first() === null) {
            return true;
        }
        return false;
    }
}
