<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
   public $timestamps = false;

   public function ingredients()
   {
      /*
       * Egy unit kategória tartozik egy alapanyaghoz
       * Katógira:  1 - tömeg
       *            2 - űrmérték
       */
      return $this->belongsTo('App\Ingredient');
   }
}
