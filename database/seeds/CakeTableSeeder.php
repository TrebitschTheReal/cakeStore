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
       $newRecipe->desc = 'This is just a description for the cake.';
       $newRecipe->save();

       $newRecipe->required_ingredients()->attach(1, array('ingredient_quantity' => 4, 'ingredient_price' => 4 * 32.4000));
       $newRecipe->required_ingredients()->attach(3, array('ingredient_quantity' => 121, 'ingredient_price' => 121 * 1.6778));


       $newRecipe = new Cake();
       $newRecipe->name = 'Feketeerdő torta';
       $newRecipe->desc = 'This is just a description for the cake.';
       $newRecipe->save();

       $newRecipe->required_ingredients()->attach(5, array('ingredient_quantity' => 145, 'ingredient_price' => 145 * 0.8397));
       $newRecipe->required_ingredients()->attach(4, array('ingredient_quantity' => 230, 'ingredient_price' => 230 * 2.5465 ));
       $newRecipe->required_ingredients()->attach(1, array('ingredient_quantity' => 6, 'ingredient_price' => 3 * 32.4000));


/*        DB::table('cakes')->insert([
            'name' => 'Dobos torta',
            'desc' => 'This is just a description for the cake.',
            'ingredients_price_sum' => 0,
            'work_hours' => 3,
            'work_price' => 0,
            'tax' => 27,
            'selling_price' => 0,
            'profit' => 0,
            ]);

        DB::table('cakes')->insert([
            'name' => 'Feketeerdő torta',
            'desc' => 'This is just a description for the cake.',
            'ingredients_price_sum' => 0,
            'work_hours' => 3,
            'work_price' => 0,
            'tax' => 27,
            'selling_price' => 0,
            'profit' => 0,
        ]);

        DB::table('cakes')->insert([
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
