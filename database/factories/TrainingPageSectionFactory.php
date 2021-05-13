<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\TrainingPageSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingPageSectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrainingPageSection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'             => $this->faker->sentence,
            'parent_id'         => null,
            'department_id'     => Department::factory(),
            'region_id'         => null,
            'department_folder' => true,
        ];
    }
}
