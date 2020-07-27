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
        'bill'        => $faker->word,
        'pay'         => $faker->text,
        'financing'   => $faker->word,
        'adders'      => $faker->randomFloat(),
        'epc'         => $faker->randomFloat(),
        'comission'   => $faker->randomFloat(),
        'setter_fee'  => $faker->randomFloat(),
        'setter'      => $faker->name
    ];
});
