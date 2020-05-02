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
       $ingredient->desc = 'Description';
       $ingredient->unit_category = 3;
       $ingredient->uploaded_unit_quantity = 10;
       $ingredient->uploaded_unit_type = 'db';
       $ingredient->uploaded_unit_price = 320;
       $ingredient->unit_price = 32.4;
       $ingredient->save();

       $ingredient = new Ingredient();
       $ingredient->name = 'tej';
       $ingredient->desc = 'Description';
       $ingredient->unit_category = 2;
       $ingredient->uploaded_unit_quantity = 1;
       $ingredient->uploaded_unit_type = 'l';
       $ingredient->uploaded_unit_price = 158;
       $ingredient->unit_price = 0.158;
       $ingredient->save();


       $ingredient = new Ingredient();
       $ingredient->name = 'étolaj';
       $ingredient->desc = 'Description';
       $ingredient->unit_category = 2;
       $ingredient->uploaded_unit_quantity = 1;
       $ingredient->uploaded_unit_type = 'l';
       $ingredient->uploaded_unit_price = 158;
       $ingredient->unit_price = 0.452;
       $ingredient->save();


       $ingredient = new Ingredient();
       $ingredient->name = 'cukor';
       $ingredient->desc = 'Description';
       $ingredient->unit_category = 1;
       $ingredient->uploaded_unit_quantity = 1;
       $ingredient->uploaded_unit_type = 'kg';
       $ingredient->uploaded_unit_price = 235;
       $ingredient->unit_price = 0.235;
       $ingredient->save();


       $ingredient = new Ingredient();
       $ingredient->name = 'liszt';
       $ingredient->desc = 'Description';
       $ingredient->unit_category = 1;
       $ingredient->uploaded_unit_quantity = 1;
       $ingredient->uploaded_unit_type = 'kg';
       $ingredient->uploaded_unit_price = 195;
       $ingredient->unit_price = 0.195;
       $ingredient->save();
    }
}
