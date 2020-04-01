<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cake extends Model
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function required_ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'required_ingredients')
            ->withPivot(['ingredient_quantity', 'ingredient_price']);
    }

}
