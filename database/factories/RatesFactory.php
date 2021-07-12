<?php

namespace Database\Factories;

use App\Enum\Role;
use App\Models\Rates;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class RatesFactory extends Factory
{
    protected $model = Rates::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'time' => 20,
            'role' => Arr::random(Role::getValues()),
            'rate' => Arr::random(range(10, 20)),
        ];
    }
}
