<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\UserEniumPointLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserEniumPointLevelFactory extends Factory
{
    protected $model = UserEniumPointLevel::class;

    public function definition()
    {
        return [
            'level'            => $this->faker->randomNumber(),
            'point'            => $this->faker->randomFloat(),
            'monthly_residual' => $this->faker->randomFloat()
        ];
    }
}
