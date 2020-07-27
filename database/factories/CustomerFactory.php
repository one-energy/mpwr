<?php


use App\Models\User;
use App\Models\Customer;
use Faker\Generator as Faker;
// use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Customer::class, function (Faker $faker) {
    return [
        'first_name'   => $faker->firstName,
        'last_name'    => $faker->lastName,
        'system_size'  => $faker->randomFloat(2, 1, 100),
        'redline'      => $faker->randomFloat(2, 1, 100),
        'bill'         => $faker->word,
        'pay'          => $faker->randomFloat(2, 1, 100),
        'financing'    => $faker->word,
        'adders'       => $faker->randomFloat(2, 1, 100),
        'epc'          => $faker->randomFloat(2, 1, 100),
        'commission'   => $faker->randomFloat(2, 1, 100),
        'setter_fee'   => $faker->randomFloat(2, 1, 100),
        'is_active'    => $faker->boolean(),
        'setter_id'    => function () {
            return factory(User::class)->create()->id;
        },
        'opened_by_id' => function () {
            return factory(User::class)->create()->id;
        }
    ];
});
