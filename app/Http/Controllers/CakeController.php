<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class CakeController extends Controller
{
    public function index() {
        $cakes = App\Cake::all();

        return view('cakes/cakeList');
    }

    public function getAllCakesData() {
        $cakes = App\Cake::all();

        return $cakes;
        //return response()->json(App\Cake::all()->toArray());
    }
}
