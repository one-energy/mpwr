<?php

use App\Term;
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
        factory(Term::class)->create(
            [
                'id' => 1,
                'value' => '25Y $15/mo. 3.99%'
            ]
        );

        factory(Term::class)->create(
            [
                'id' => 2,
                'value' => '25Y $15/mo. 2.99%'
            ]
        );

        factory(Term::class)->create(
            [
                'id' => 3,
                'value' => '25Y Standard 3.99%'
            ]
        );

        factory(Term::class)->create(
            [
                'id' => 4,
                'value' => '25Y Standard 2.99%'
            ]
        );
    }
}
