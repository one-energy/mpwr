<?php

namespace Database\Factories;

use App\Models\Rates;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatesFactory extends Factory
{
    protected $model = Rates::class;
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'time' => 20,
        ];
    }
}
