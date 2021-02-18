<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Term;
use Faker\Generator as Faker;

$factory->define(Term::class, function (Faker $faker) {
    return [
        'value' => '25Y $15/mo. 3.99%'
    ];
});
