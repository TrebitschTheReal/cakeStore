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
Route::get('/recipes', 'CakeController@createRecipeView')->name('recipes')->middleware('auth');
Route::get('/users', 'UserController@getUserView')->name('users')->middleware('auth');

//POST végpontok receptekhez
Route::post('/fillnewlycreatedrecipe', 'CakeController@fillNewlyCreatedRecipe')->name('fillnewlycreatedrecipe')->middleware('auth');
Route::post('/registernewrecipe', 'CakeController@registerNewRecipe')->name('registernewrecipe')->middleware('auth');
Route::post('/registernewingredient', 'IngredientController@registerNewIngredient')->name('registernewingredient')->middleware('auth');
Route::post('/modifyexistingingredient', 'IngredientController@modifyExistingIngredient')->name('modifyexistingingredient')->middleware('auth');

//POST végpontok userekhez


//API a torta receptekhez
Route::get('/cakelist', 'CakeController@getAllCakeRecipes')->name('cakelist')->middleware('auth');
Route::get('/fetchingredients', 'IngredientController@fetchIngredients')->name('fetchingredients')->middleware('auth');
Route::post('/modifyrecipe', 'CakeController@modifyRecipe')->name('modifyrecipe')->middleware('auth');

//API userekhez - ehhez a végponthoz csak az 'admin' role-al rendelkező felhasználók férhetnek hozzá
Route::get('/getuserlist', 'UserController@getUserList')->name('getuserlist')->middleware('role:admin');


Auth::routes();

