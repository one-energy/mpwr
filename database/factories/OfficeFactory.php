<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->company,
        ];
    }
}
