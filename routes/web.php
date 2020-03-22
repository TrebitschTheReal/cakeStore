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
Route::get('/cakes', 'CakeController@index')->name('cakes')->middleware('auth');

//API for Cakes
Route::get('/cakelist', 'CakeController@getAllCakesData')->name('cakelist')->middleware('auth');

Auth::routes();

