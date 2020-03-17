<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    public function cakes()
    {
        return $this->belongsToMany(Cake::class, 'required_ingredients');
    }
}
