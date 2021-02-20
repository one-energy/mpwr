<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Financer;
use Faker\Generator as Faker;

$factory->define(Financer::class, function (Faker $faker) {
    return [
        'name' => 'Enium'
    ];
});
