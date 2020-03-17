<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class CakeController extends Controller
{
    public function getAllCakes() {
        $cakes = App\Cake::all();

        return view('home', compact('cakes'));
    }
}
