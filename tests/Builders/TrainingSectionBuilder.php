<?php


namespace Tests\Builders;

use App\Models\TrainingPageSection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class TrainingSectionBuilder
{
    use WithFaker;

    /** @var TrainingPageSection */
    public $section;

    public function __construct($attributes = [])
    {
        $this->faker = $this->makeFaker('en_US');
        $this->section  = (new TrainingPageSection)->forceFill(array_merge([
            'title' => Str::title($this->faker->name),
        ], $attributes));
    }

    public function save()
    {
     
        $this->section->save();

        return $this;
    }

    public function get()
    {
        return $this->section;
    }
}