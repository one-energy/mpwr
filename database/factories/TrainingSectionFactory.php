<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\TrainingPageSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingSectionFactory extends Factory
{
    protected $model = TrainingPageSection::class;

    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'department_id' => Department::factory()
        ];
    }
}
