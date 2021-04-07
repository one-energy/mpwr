<?php

namespace Database\Factories;

use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    protected $model = Office::class;

    public function definition()
    {
        return [
            'name'              => $this->faker->company,
            'region_id'         => Region::factory(),
            'office_manager_id' => User::factory(),
        ];
    }
}
