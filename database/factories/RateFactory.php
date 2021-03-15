<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RateFactory extends Factory
{
    protected $model = Rate::class;
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'time' => 20,
        ];
    }
}
