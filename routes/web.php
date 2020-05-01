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

/*
 * Admin végpontok
 * Végpont meghívása esetén a request legelőször a middlewaren megy keresztül, ahol paraméterként megkapja
 * a 'role: ' után megadott értéket: admin, stb.
 * middleware: RoleMiddleWare.php
 */
Route::middleware('role:admin')->group( function() {
    // VIEWS
    Route::get('/users', 'UserController@getUserView')->name('users');

    //POST
   Route::post('/deleteuser', 'UserController@deleteUser')->name('deleteuser');

    //API
    Route::get('/getuserlist', 'UserController@getUserList')->name('getuserlist');
});

/*
 * Manager végpontok
 */
Route::middleware('role:admin,manager')->group( function() {
    // VIEWS
    Route::get('/cakerecipes', 'CakeController@index')->name('cakerecipes');
    Route::get('/recipes', 'CakeController@createRecipeView')->name('recipes');

    //POST
    Route::post('/fillnewlycreatedrecipe', 'CakeController@fillNewlyCreatedRecipe')->name('fillnewlycreatedrecipe');
    Route::post('/registernewrecipe', 'CakeController@registerNewRecipe')->name('registernewrecipe');
    Route::post('/registernewingredient', 'IngredientController@registerNewIngredient')->name('registernewingredient');
    Route::post('/modifyexistingingredient', 'IngredientController@modifyExistingIngredient')->name('modifyexistingingredient');
   Route::post('/deleterecipe', 'CakeController@deleteRecipe')->name('deleterecipe');
   Route::post('/deleteingredient', 'IngredientController@deleteIngredient')->name('deleteingredient');


    //API
    Route::get('/cakelist', 'CakeController@getAllCakeRecipes')->name('cakelist');
   Route::get('/fetchingredients', 'IngredientController@fetchIngredients')->name('fetchingredients');
    Route::post('/modifyrecipe', 'CakeController@modifyRecipe')->name('modifyrecipe');
       Route::get('/fetchunittypes', 'IngredientController@fetchUnitTypes')->name('fetchunittypes');

});

/*
 * Reg végpontok
 */
Route::middleware('role:admin,manager,reg')->group( function() {
    Route::get('/home', 'HomeController@index')->name('home');
});


Auth::routes();

