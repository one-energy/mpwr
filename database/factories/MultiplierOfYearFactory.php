<?php

namespace Database\Factories;

use App\Models\MultiplierOfYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class MultiplierOfYearFactory extends Factory
{
    protected $model = MultiplierOfYear::class;

    public function definition()
    {
        return [
            'multiplier' => $this->faker->randomFloat(2, 1, 5),
            'year'       => $this->faker->year()
        ];
    }
}
