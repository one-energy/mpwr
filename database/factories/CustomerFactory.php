<?php


use App\Models\User;
use App\Models\Customer;
use App\Models\Financing;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Customer::class, function (Faker $faker) {

    $epc        = $faker->randomFloat(2, 1, 1000);
    $pay        = $epc * 0.8;
    $setterFee  = $pay * 0.2;
    $systemSize = 20;
    $adders     = 20;

    return [
        'first_name'    => $faker->firstName,
        'last_name'     => $faker->lastName,
        'system_size'   => $systemSize,
        'bill'          => $faker->word,
        'pay'           => $pay,
        'financing'     => $faker->word,
        'financing_id'  => 1,
        'adders'        => $adders,
        'epc'           => $epc+100,
        'commission'    => (($epc - ( $pay + $setterFee )) * $systemSize) - $adders,
        'setter_fee'    => $setterFee,
        'sales_rep_fee' => 0,
        'panel_sold'    => $faker->boolean(),
        'is_active'     => $faker->boolean(),
        'setter_id'     => function () {
            return factory(User::class)->create()->id;
        },
        'sales_rep_id'     => function () {
            return factory(User::class)->create()->id;
        },
        'opened_by_id' => 1,
        'enium_points' => 0
    ];
});
