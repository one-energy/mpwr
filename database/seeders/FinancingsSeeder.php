<?php

namespace Database\Seeders;

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
        Financing::factory()->create(
            [
                'id' => 1,
                'name' => 'Purchase'
            ]
        );

        Financing::factory()->create(
            [
                'id' => 2,
                'name' => 'PPA'
            ]
        );

        Financing::factory()->create(
            [
                'id' => 3,
                'name' => 'PACE'
            ]
        );
    }
}
