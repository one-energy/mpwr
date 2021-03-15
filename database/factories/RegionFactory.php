<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->city,
        ];
    }

}
