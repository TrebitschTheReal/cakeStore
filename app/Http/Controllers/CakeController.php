<?php

namespace App\Http\Controllers;

use App\Http\Services\CakeService;
use Illuminate\Http\Request;
use App\Cake;

class CakeController extends Controller
{
    public function index() {
        return view('cakes/cakeRecipeList');
    }

    public function getAllCakeRecipes() {
        $cakes = Cake::with('required_ingredients')->get();
        $cakeService = new CakeService();
        $cakeService->generateSumIngredientsPriceForAllCakes($cakes);




        return $cakes;

        //Elvileg ez ugyan ezt csinálja, csak a Laravel stringifyolja már az elején.
        //return response()->json($cakes);
    }
}
