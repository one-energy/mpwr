<?php

namespace Database\Factories;

use App\Models\EniumPointsCalculationBasis;
use Illuminate\Database\Eloquent\Factories\Factory;

class EniumPointsCalculationBasisFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EniumPointsCalculationBasis::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'                 => $this->faker->name(),
            'noble_pay_dealer_fee' => $this->faker->randomFloat(3, max:1),
            'rep_residual'         => $this->faker->randomFloat(3, max:1),
            'amount'               => $this->faker->randomFloat(2),
        ];
    }
}
