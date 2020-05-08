<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CakeTableSeeder::class);
        $this->call(IngredientSeeder::class);
        $this->call(RequiredIngredientsSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UnitsSeeder::class);
    }
}

/* Használaton kívül:
 * $this->call(UsersTableSeeder::class);
 */
