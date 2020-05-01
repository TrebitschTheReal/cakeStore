<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    public function cakes()
    {
        return $this->belongsToMany(Cake::class, 'required_ingredients')
            ->withPivot(['ingredient_quantity', 'ingredient_price', 'ingredient_unit_type']);
    }

    /*
     * Egy alapanyagnak több unit típusa lehet a kategóriáján belül.
     * Katógira:  1 - tömeg
     *            2 - űrmérték
     *
     * Ingredient::first()->unit
     *
     * listázza azokat a találatokat, ahol az ingredients táblában a unit_category egyezik a units tábla unit_categoryval
     */
   public function unit()
   {
      return $this->hasMany('App\Unit','unit_category','unit_category');
   }
}
