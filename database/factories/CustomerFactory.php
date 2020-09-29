<?php


use App\Models\User;
use App\Models\Customer;
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
        'first_name'   => $faker->firstName,
        'last_name'    => $faker->lastName,
        'system_size'  => $systemSize,
        'bill'         => $faker->word,
        'pay'          => $pay,
        'financing'    => $faker->word,
        'adders'       => $adders,
        'epc'          => $epc+100,
        'commission'   => (($epc - ( $pay + $setterFee )) * $systemSize) - $adders,
        'setter_fee'   => $setterFee,
        'panel_sold'   => $faker->boolean(),
        'is_active'    => $faker->boolean(),
        'setter_id'    => function () {
            return factory(User::class)->create()->id;
        },
        'opened_by_id' => 1
    ];
});
