<?php


use App\Models\Region;
use Faker\Generator as Faker;

$factory->define(Region::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
    ];
});
