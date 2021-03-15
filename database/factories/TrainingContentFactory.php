<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingContentFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'video_url' => 'https://youtu.be/cu9lJvjERPQ'
        ];
    }
}
