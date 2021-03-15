<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingSectionFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->name
        ];
    }
}
