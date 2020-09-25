<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TrainingPageContent;
use Faker\Generator as Faker;

$factory->define(TrainingPageContent::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->text,
        'video_url' => 'https://youtu.be/cu9lJvjERPQ'
    ];
});
