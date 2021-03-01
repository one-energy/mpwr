<?php

use App\Models\Customer;
use App\Models\Financing;
use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Date;

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
        'date_of_sale'  => Carbon::now(),
        'financing_id'  => factory(Financing::class)->create()->id,
        'adders'        => $adders,
        'epc'           => $epc + 100,
        'margin'        => $epc - $setterFee,
        'commission'    => (($epc - ($pay + $setterFee)) * $systemSize) - $adders,
        'setter_fee'    => $setterFee,
        'sales_rep_fee' => 0,
        'panel_sold'    => $faker->boolean(),
        'is_active'     => $faker->boolean(),
        'setter_id'     => factory(User::class)->create()->id,
        'sales_rep_id'  => factory(User::class)->create()->id,
        'opened_by_id'  => factory(User::class)->create()->id,
        'enium_points'  => 0,
    ];
});
