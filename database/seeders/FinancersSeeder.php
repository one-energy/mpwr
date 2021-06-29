<?php

namespace Database\Seeders;

use App\Models\Financer;
use Illuminate\Database\Seeder;

class FinancersSeeder extends Seeder
{
    public function run()
    {
        Financer::factory()->create(
            [
                'id' => 1,
                'name' => 'Enium'
            ]
        );

        Financer::factory()->create(
            [
                'id' => 2,
                'name' => 'Other'
            ]
        );
    }
}
