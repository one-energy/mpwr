<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Office;
use Faker\Generator as Faker;

$factory->define(Office::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
    ];
});
