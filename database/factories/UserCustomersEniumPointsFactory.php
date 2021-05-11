<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use App\Models\UserCustomersEniumPoints;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCustomersEniumPointsFactory extends Factory
{

    protected $model = UserCustomersEniumPoints::class;

    public function definition()
    {
        return [
            'user_sales_rep_id' => User::factory(),
            'customer_id'       => Customer::factory(),
            'points'            => $this->faker->randomNumber()
        ];
    }
}
