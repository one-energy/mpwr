<?php


use App\Models\Region;
use Faker\Generator as Faker;

/** @var Factory $factory */
$factory->define(Region::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
    ];
});
