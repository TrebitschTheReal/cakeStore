<?php

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
        DB::table('cakes')->insert([
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
        ]);
    }
}
