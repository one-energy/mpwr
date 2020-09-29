<?php

use App\Models\Department;
use Faker\Generator as Faker;

/** @var Factory $factory */
$factory->define(Department::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
    ];
});
