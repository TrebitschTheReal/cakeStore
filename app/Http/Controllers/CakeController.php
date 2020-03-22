<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class CakeController extends Controller
{
    public function index() {
        $cakes = App\Cake::all();

        return view('cakes/cakeList', compact('cakes'));
    }
}
