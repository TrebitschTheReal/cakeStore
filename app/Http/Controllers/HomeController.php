<?php

namespace App\Http\Controllers;

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
        $cake = Cake::where('id', 2)->first();

        return view('home', compact('cake'));
    }
}
