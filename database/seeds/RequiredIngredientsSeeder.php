<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RequiredIngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('required_ingredients')->insert([
            'ingredient_id' => 1,
            'ingredient_quantity' => 3,
            'cake_id' => 1,
            'ingredient_price' => 9,
        ]);

        DB::table('required_ingredients')->insert([
            'ingredient_id' => 2,
            'ingredient_quantity' => 1,
            'cake_id' => 1,
            'ingredient_price' => 63,
        ]);
    }
}
