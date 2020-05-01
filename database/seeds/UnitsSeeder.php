<?php

use App\Unit;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      $unit = new Unit();
      $unit->unit_category = 1;
      $unit->category_name = 'tömeg';
      $unit->type_name = 'g';
      $unit->conversion_rate = 1;
      $unit->save();

      $unit = new Unit();
      $unit->unit_category = 1;
      $unit->category_name = 'tömeg';
      $unit->type_name = 'dkg';
      $unit->conversion_rate = 10;
      $unit->save();

      $unit = new Unit();
      $unit->unit_category = 1;
      $unit->category_name = 'tömeg';
      $unit->type_name = 'kg';
      $unit->conversion_rate = 1000;
      $unit->save();

      $unit = new Unit();
      $unit->unit_category = 2;
      $unit->category_name = 'térfogat';
      $unit->type_name = 'ml';
      $unit->conversion_rate = 1;
      $unit->save();

      $unit = new Unit();
      $unit->unit_category = 2;
      $unit->category_name = 'térfogat';
      $unit->type_name = 'cl';
      $unit->conversion_rate = 10;
      $unit->save();

      $unit = new Unit();
      $unit->unit_category = 2;
      $unit->category_name = 'térfogat';
      $unit->type_name = 'dl';
      $unit->conversion_rate = 100;
      $unit->save();

      $unit = new Unit();
      $unit->unit_category = 2;
      $unit->category_name = 'térfogat';
      $unit->type_name = 'l';
      $unit->conversion_rate = 1000;
      $unit->save();

      $unit = new Unit();
      $unit->unit_category = 3;
      $unit->category_name = 'darab';
      $unit->type_name = 'db';
      $unit->conversion_rate = 1;
      $unit->save();

   }
}
