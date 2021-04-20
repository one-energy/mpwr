<?php

namespace Database\Factories;

use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingPageContentFactory extends Factory
{
    protected $model = TrainingPageContent::class;

    public function definition()
    {
        return [
            'title'                    => $this->faker->sentence,
            'description'              => $this->faker->text,
            'training_page_section_id' => TrainingPageSection::factory(),
            'video_url'                => $this->faker->url,
        ];
    }
}
