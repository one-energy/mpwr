<?php

namespace Database\Seeders;

use App\Models\CustomersStockPoints;
use Illuminate\Database\Seeder;

class CustomersStockPointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomersStockPoints::factory()->count(5)->create();
    }
}
