<?php

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

       DB::table('ingredients')->insert([
          'name' => 'tojás',
          'desc' => 'This is just a description for ingredient.',
          'unit_category' => 3,
          'unit_price' => 21,
       ]);

        DB::table('ingredients')->insert([
            'name' => 'cukor',
            'desc' => 'This is just a description for ingredient.',
            'unit_category' => 1,
            'unit_price' => 3,
        ]);

        DB::table('ingredients')->insert([
            'name' => 'tej',
            'desc' => 'This is just a description for ingredient.',
            'unit_category' => 2,
            'unit_price' => 4,
        ]);

        DB::table('ingredients')->insert([
            'name' => 'étolaj',
            'desc' => 'This is just a description for ingredient.',
            'unit_category' => 2,
            'unit_price' => 2,
        ]);
    }
}
