<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cake extends Model
{
    private $ingredientsPriceSum;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function required_ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'required_ingredients')
            ->withPivot(['ingredient_quantity', 'ingredient_price']);
    }

    /**
     * @return int
     */
    public function getIngredientsPriceSum(): int
    {
        foreach ($this->required_ingredients as $ingredient) {
            $this->ingredientsPriceSum += $ingredient->pivot->ingredient_price;
        }

        return $this->ingredientsPriceSum;
    }

    /**
     * @param int $ingredientsPriceSum
     */
    protected function setIngredientsPriceSum(int $ingredientsPriceSum): void
    {
        $this->ingredientsPriceSum = $ingredientsPriceSum;
    }




}
