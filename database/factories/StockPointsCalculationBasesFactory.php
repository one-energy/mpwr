<?php

namespace Database\Factories;

use App\Models\StockPOintsCalculationBases;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockPointsCalculationBasesFactory extends Factory
{
    protected $model = StockPointsCalculationBases::class;

    public function definition()
    {
        return [
            'name'             => $this->faker->title(),
            'stock_base_point' => $this->faker->randomNumber()
        ];
    }
}
