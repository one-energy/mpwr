<?php


use App\Models\Customer;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** @var Factory $factory */
$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'system_size' => $faker->randomFloat(),
        'redline'     => $faker->randomFloat(),
        'bill'        => $faker->randomFloat(),
        'pay'         => $faker->text,
        'financing'   => $faker->randomFloat(),
        'adders'      => $faker->randomFloat(),
        'gross_ppw'   => $faker->randomFloat(),
        'comission'   => $faker->randomFloat(),
        'setter_fee'  => $faker->randomFloat(),
        'setter'      => $faker->name
    ];
});
