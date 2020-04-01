<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Views
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/cakerecipes', 'CakeController@index')->name('cakerecipes')->middleware('auth');

//API for cake recipes
Route::get('/cakelist', 'CakeController@getAllCakeRecipes')->name('cakelist')->middleware('auth');

Auth::routes();

