<?php

use App\Models\DailyNumber;
use Faker\Generator as Faker;

$factory->define(DailyNumber::class, function (Faker $faker) {
    return [
        'date'       => $faker->date($format = 'Y-m-d', $max = 'now'),
        'doors'      => rand(1, 100),
        'hours'      => rand(1, 100),
        'sets'       => rand(1, 100),
        'sits'       => rand(1, 100),
        'set_closes' => rand(1, 100),
        'closes'     => rand(1, 100)
    ];
});
