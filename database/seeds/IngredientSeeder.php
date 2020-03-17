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
            'name' => 'cukor',
            'desc' => 'This is just a description for ingredient.',
            'unit_type' => 'g',
            'unit_price' => 3,
        ]);

        DB::table('ingredients')->insert([
            'name' => 'tojás',
            'desc' => 'This is just a description for ingredient.',
            'unit_type' => 'db',
            'unit_price' => 21,
        ]);

        DB::table('ingredients')->insert([
            'name' => 'vaj',
            'desc' => 'This is just a description for ingredient.',
            'unit_type' => 'g',
            'unit_price' => 4,
        ]);

        DB::table('ingredients')->insert([
            'name' => 'liszt',
            'desc' => 'This is just a description for ingredient.',
            'unit_type' => 'g',
            'unit_price' => 2,
        ]);
    }
}
