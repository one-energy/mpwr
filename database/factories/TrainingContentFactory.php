<?php

namespace Database\Factories;

use App\Models\TrainingPageContent;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingContentFactory extends Factory
{
    protected $model = TrainingPageContent::class;

    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'video_url' => 'https://youtu.be/cu9lJvjERPQ'
        ];
    }
}
