<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cake;

class CakeController extends Controller
{
    public function index() {
        $cakes = Cake::all();

        return view('cakes/cakeRecipeList');
    }

    public function getAllCakeRecipes() {
        $cakes = Cake::all();
        $cakes = Cake::with('required_ingredients')->get();

        //return $cakes;
        //return response()->json(App\Cake::all()->toArray());

        return response()->json($cakes);
    }
}
