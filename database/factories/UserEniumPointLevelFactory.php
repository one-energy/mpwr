<?php

namespace Database\Factories;

use App\Models\UserEniumPointLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserEniumPointLevelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserEniumPointLevel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'level'            => $this->faker->randomNumber(),
            'point'            => $this->faker->randomNumber(),
            'monthly_residual' => $this->faker->randomNumber()
        ];
    }
}
