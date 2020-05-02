<?php

use App\Cake;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CakeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $newRecipe = new Cake();
       $newRecipe->name = 'Dobos torta';
       $newRecipe->desc = 'Minta recept. Az értékek lehet, hogy nem stimmelnek. A helyes működés ellenőrzéséhez módosítsd ezt a tortát, vagy készíts egy újat';
       $newRecipe->save();

       $newRecipe->required_ingredients()->attach(1, array('ingredient_quantity' => 4, 'ingredient_price' => 4 * 32.4000, 'ingredient_unit_type' => 'db'));
       $newRecipe->required_ingredients()->attach(3, array('ingredient_quantity' => 121, 'ingredient_price' => 121 * 0.452, 'ingredient_unit_type' => 'ml'));


       $newRecipe = new Cake();
       $newRecipe->name = 'Feketeerdő torta';
       $newRecipe->desc = 'Minta recept. Az értékek lehet, hogy nem stimmelnek. A helyes működés ellenőrzéséhez módosítsd ezt a tortát, vagy készíts egy újat';
       $newRecipe->save();

       $newRecipe->required_ingredients()->attach(5, array('ingredient_quantity' => 145, 'ingredient_price' => 145 * 0.195, 'ingredient_unit_type' => 'g'));
       $newRecipe->required_ingredients()->attach(4, array('ingredient_quantity' => 15, 'ingredient_price' => 150 * 0.235, 'ingredient_unit_type' => 'dkg'));
       $newRecipe->required_ingredients()->attach(1, array('ingredient_quantity' => 6, 'ingredient_price' => 3 * 32.4000, 'ingredient_unit_type' => 'db'));


/*        DB::table('cakes')->insert([
            'name' => 'Nutellás torta',
            'desc' => 'This is just a description for the cake.',
            'ingredients_price_sum' => 0,
            'work_hours' => 3,
            'work_price' => 0,
            'tax' => 27,
            'selling_price' => 0,
            'profit' => 0,
        ]);*/
    }
}
