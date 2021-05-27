<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Financing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        $epc        = $this->faker->randomFloat(2, 1, 8000);
        $pay        = $epc * 0.8;
        $setterFee  = $pay * 0.2;
        $systemSize = 20;
        $adders     = 20;

        return [
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'system_size'   => $systemSize,
            'bill'          => $this->faker->word,
            'pay'           => $pay,
            'financing'     => $this->faker->word,
            'date_of_sale'  => Carbon::now(),
            'financing_id'  => Financing::factory()->create()->id,
            'adders'        => $adders,
            'epc'           => $epc + 100,
            'margin'        => $epc - $setterFee,
            'commission'    => (($epc - ($pay + $setterFee)) * $systemSize) - $adders,
            'setter_fee'    => $setterFee,
            'sales_rep_fee' => 0,
            'panel_sold'    => $this->faker->boolean(),
            'is_active'     => $this->faker->boolean(),
            'setter_id'     => User::factory()->create()->id,
            'sales_rep_id'  => User::factory()->create()->id,
            'opened_by_id'  => User::factory()->create()->id,
            'enium_points'  => 0,
        ];
    }
}
