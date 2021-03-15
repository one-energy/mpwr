<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RateFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'time' => 20,
        ];
    }
}
