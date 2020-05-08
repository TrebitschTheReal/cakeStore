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

/*
   Hibát generál php artisan route:cache esetén ha nem kontrollerbe irányítjuk a routeokat.
   Muszáj controllerből dolgozni, ez a Laravel bugja.
   https://stackoverflow.com/questions/45266254/laravel-unable-to-prepare-route-for-serialization-uses-closure
*/

Route::get('/', 'HomeController@guest');

Auth::routes();

/*
 * Admin végpontok
 * Végpont meghívása esetén a request legelőször a middlewaren megy keresztül, ahol paraméterként megkapja
 * a 'role: ' után megadott értéket: admin, stb.
 * middleware: RoleMiddleWare.php
 */
Route::middleware('role:admin')->group(function () {
   // VIEWS
   Route::get('/users', 'UserController@getUserView')->name('users');

   //POST
   Route::post('/deleteuser', 'UserController@deleteUser')->name('deleteuser');
   Route::post('/modifyUser', 'UserController@modifyUser')->name('modifyuser');

   //API
   Route::get('/getuserlist', 'UserController@getUserList')->name('getuserlist');
   Route::get('/getroles', 'UserController@getRoles')->name('getroles');
});

/*
 * Manager végpontok
 */
Route::middleware('role:admin,manager')->group(function () {
   // VIEWS
   Route::get('/cakerecipes', 'CakeController@index')->name('cakerecipes');
   Route::get('/recipes', 'CakeController@createRecipeView')->name('recipes');

   //POST
   Route::post('/modifyrecipe', 'CakeController@fillNewlyCreatedRecipe')->name('modifyrecipe');
   Route::post('/registernewrecipe', 'CakeController@registerNewRecipe')->name('registernewrecipe');
   Route::post('/manageingredient', 'IngredientController@manageIngredient')->name('manageingredient');
   Route::post('/deleterecipe', 'CakeController@deleteRecipe')->name('deleterecipe');
   Route::post('/deleteingredient', 'IngredientController@deleteIngredient')->name('deleteingredient');
   Route::post('/getmodifiablerecipe', 'CakeController@getmodifiableRecipe')->name('getmodifiablerecipe');

   //API
   Route::get('/cakelist', 'CakeController@getAllCakeRecipes')->name('cakelist');
   Route::get('/fetchingredients', 'IngredientController@fetchIngredients')->name('fetchingredients');
   Route::get('/fetchunittypes', 'IngredientController@fetchUnitTypes')->name('fetchunittypes');

});

/*
 * Reg végpontok
 */
Route::middleware('role:admin,manager,reg')->group(function () {
   Route::get('/home', 'HomeController@index')->name('home');
});


Auth::routes();

