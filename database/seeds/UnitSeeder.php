<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
            'unit_type' => 'g',
        ]);

        DB::table('units')->insert([
            'unit_type' => 'dkg',
        ]);

        DB::table('units')->insert([
            'unit_type' => 'kg',
        ]);

        DB::table('units')->insert([
            'unit_type' => 'cl',
        ]);

        DB::table('units')->insert([
            'unit_type' => 'dl',
        ]);

        DB::table('units')->insert([
            'unit_type' => 'l',
        ]);

        DB::table('units')->insert([
            'unit_type' => 'db',
        ]);
    }
}
