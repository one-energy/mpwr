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
        Term::factory()->create(
            [
                'id' => 1,
                'value' => '25Y $15/mo. 3.99%'
            ]
        );

        Term::factory()->create(
            [
                'id' => 2,
                'value' => '25Y $15/mo. 2.99%'
            ]
        );

        Term::factory()->create(
            [
                'id' => 3,
                'value' => '25Y Standard 3.99%'
            ]
        );

        Term::factory()->create(
            [
                'id' => 4,
                'value' => '25Y Standard 2.99%'
            ]
        );
    }
}
