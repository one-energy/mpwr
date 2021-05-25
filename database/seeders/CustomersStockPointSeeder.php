<?php

namespace Database\Seeders;

use App\Models\CustomersStockPoint;
use Illuminate\Database\Seeder;

class CustomersStockPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomersStockPoint::factory()->count(5)->create();
    }
}
