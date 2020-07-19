<?php


use App\Models\Customer;
use Faker\Generator as Faker;
// use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Customer::class, function (Faker $faker) {
    return [
        'first_name'  => $faker->firstName,
        'last_name'   => $faker->lastName,
        'system_size' => $faker->randomFloat(2, 1, 100),
        'redline'     => $faker->randomFloat(2, 1, 100),
        'bill'        => $faker->randomFloat(2, 1, 100),
        'pay'         => $faker->sentence(3),
        'financing'   => $faker->randomFloat(2, 1, 100),
        'adders'      => $faker->randomFloat(2, 1, 100),
        'gross_ppw'   => $faker->randomFloat(2, 1, 100),
        'comission'   => $faker->randomFloat(2, 1, 100),
        'setter_fee'  => $faker->randomFloat(2, 1, 100),
        'setter'      => $faker->name,
        'is_active'   => $faker->boolean()
    ];
});
