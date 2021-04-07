<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition()
    {
        return [
            'name'              => $this->faker->city,
            'department_id'     => null,
            'region_manager_id' => null
        ];
    }
}
