<?php

use App\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       $ingredient = new Ingredient();
       $ingredient->name = 'tojás';
       $ingredient->desc = 'This is just a description for ingredient.';
       $ingredient->unit_category = 3;
       $ingredient->unit_price = 32.4;
       $ingredient->save();

       $ingredient = new Ingredient();
       $ingredient->name = 'tej';
       $ingredient->desc = 'This is just a description for ingredient.';
       $ingredient->unit_category = 2;
       $ingredient->unit_price = 1.67783573;
       $ingredient->save();


       $ingredient = new Ingredient();
       $ingredient->name = 'étolaj';
       $ingredient->desc = 'This is just a description for ingredient.';
       $ingredient->unit_category = 2;
       $ingredient->unit_price = 1.353423423554;
       $ingredient->save();


       $ingredient = new Ingredient();
       $ingredient->name = 'cukor';
       $ingredient->desc = 'This is just a description for ingredient.';
       $ingredient->unit_category = 1;
       $ingredient->unit_price = 2.5465;
       $ingredient->save();


       $ingredient = new Ingredient();
       $ingredient->name = 'liszt';
       $ingredient->desc = 'This is just a description for ingredient.';
       $ingredient->unit_category = 1;
       $ingredient->unit_price = 0.83967876867867678;
       $ingredient->save();

    }
}
