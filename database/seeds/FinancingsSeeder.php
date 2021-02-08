<?php

use App\Models\Financing;
use Illuminate\Database\Seeder;

class FinancingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Financing::class)->create(
            [
                'id' => 1,
                'name' => 'Purchase'
            ]
        );

        factory(Financing::class)->create(
            [
                'id' => 2,
                'name' => 'PPA'
            ]
        );

        factory(Financing::class)->create(
            [
                'id' => 3,
                'name' => 'PACE'
            ]
        );
    }
}
