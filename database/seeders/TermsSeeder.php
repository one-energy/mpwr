<?php

namespace Database\Seeders;

use App\Models\Term;
use Illuminate\Database\Seeder;

class TermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Term::factory([
            'id'                   => 1,
            'value'                => '25Y $15/mo. 3.99%',
            'noble_pay_dealer_fee' => 0.015,
            'rep_residual'         => 0.025,
            'amount'               => 480
        ])->create();

        Term::factory([
            'id'                   => 2,
            'value'                => '25Y $15/mo. 2.99%',
            'noble_pay_dealer_fee' => 0.22,
            'rep_residual'         => 0.015,
            'amount'               => 800
        ])->create();

        Term::factory([
            'id'                   => 3,
            'value'                => '25Y Standard 3.99%',
            'noble_pay_dealer_fee' => 0.015,
            'rep_residual'         => 0.025,
            'amount'               => 480
        ])->create();

        Term::factory([
            'id'                   => 4,
            'value'                => '25Y Standard 2.99%',
            'noble_pay_dealer_fee' => 0.22,
            'rep_residual'         => 0.015,
            'amount'               => 800
        ])->create();
    }
}
