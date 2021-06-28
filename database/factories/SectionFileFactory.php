<?php

namespace Database\Factories;

use App\Models\SectionFile;
use App\Models\TrainingPageSection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SectionFileFactory extends Factory
{
    protected $model = SectionFile::class;

    public function definition()
    {
        $name = Str::random();
        return [
            'training_page_section_id' => TrainingPageSection::factory(),
            'name'                     => $name,
            'original_name'            => Str::slug($this->faker->firstName),
            'type'                     => Arr::random(['jpg', 'png', 'pdf']),
            'size'                     => Arr::random(range(10, 50)),
            'path'                     => sprintf('/tmp/%s', $name),
            'training_type'            => Arr::random(['files', 'training']),
        ];
    }
}
