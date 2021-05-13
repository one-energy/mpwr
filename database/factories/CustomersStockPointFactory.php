<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomersStockPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomersStockPointFactory extends Factory
{
    protected $model = CustomersStockPoint::class;

    public function definition()
    {
        return [
            'customer_id'           => Customer::factory(),
            'stock_recruiter'       => $this->faker->randomNumber(3),
            'stock_setting'         => $this->faker->randomNumber(3),
            'stock_personal_sale'   => $this->faker->randomNumber(3),
            'stock_pod_leader_team' => $this->faker->randomNumber(3),
            'stock_manager'         => $this->faker->randomNumber(3),
            'stock_divisional'      => $this->faker->randomNumber(3),
            'stock_regional'        => $this->faker->randomNumber(3),
            'stock_department'      => $this->faker->randomNumber(3)
        ];
    }
}
