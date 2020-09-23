<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TrainingPageSection;
use Faker\Generator as Faker;

$factory->define(TrainingPageSection::class, function (Faker $faker) {
    return [
        'title' => $faker->name
    ];
});
