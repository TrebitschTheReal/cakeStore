<?php

namespace App\Http\Controllers;

use App\Http\Services\CakeService;
use Illuminate\Http\Request;
use App\Cake;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $cakes = Cake::all();


        return view('home', compact('cakes'));
    }
}
