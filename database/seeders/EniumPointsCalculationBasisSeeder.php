<?php

namespace Database\Seeders;

use App\Models\EniumPointsCalculationBasis;
use Illuminate\Database\Seeder;

class EniumPointsCalculationBasisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EniumPointsCalculationBasis::factory([
            'name'                 => '25 $15/mo. 3.99%',
            'noble_pay_dealer_fee' => 0.015,
            'rep_residual'         => 0.025,
            'amount'               => 480
        ])->create();

        EniumPointsCalculationBasis::factory([
            'name'                 => '25 $15/mo. 2.99%',
            'noble_pay_dealer_fee' => 0.22,
            'rep_residual'         => 0.015,
            'amount'               => 800
        ])->create();

        EniumPointsCalculationBasis::factory([
            'name'                 => '25 Standard 3.99%',
            'noble_pay_dealer_fee' => 0.015,
            'rep_residual'         => 0.025,
            'amount'               => 480
        ])->create();

        EniumPointsCalculationBasis::factory([
            'name'                 => '25 Standard 2.99%',
            'noble_pay_dealer_fee' => 0.22,
            'rep_residual'         => 0.015,
            'amount'               => 800
        ])->create();
    }
}
