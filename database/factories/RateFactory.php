<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Rates;
use Faker\Generator as Faker;

$factory->define(Rates::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'time' => 20,
    ];
});
