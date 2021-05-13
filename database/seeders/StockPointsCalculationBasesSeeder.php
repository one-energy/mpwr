<?php

namespace Database\Seeders;

use App\Models\StockPointsCalculationBases;
use Illuminate\Database\Seeder;

class StockPointsCalculationBasesSeeder extends Seeder
{

    public function run()
    {
        StockPointsCalculationBases::factory()->create([
            'id'               => 1,
            'name'             => 'Recruit',
            'stock_base_point' => 25
        ]);
        StockPointsCalculationBases::factory()->create([
            'id'               => 2,
            'name'             => 'Setting',
            'stock_base_point' => 100
        ]);
        StockPointsCalculationBases::factory()->create([
            'id'               => 3,
            'name'             => 'Personal Sales',
            'stock_base_point' => 250
        ]);
        StockPointsCalculationBases::factory()->create([
            'id'               => 4,
            'name'             => 'Pod Leader Team',
            'stock_base_point' => 35
        ]);
        StockPointsCalculationBases::factory()->create([
            'id'               => 5,
            'name'             => 'Manager',
            'stock_base_point' => 50
        ]);
        StockPointsCalculationBases::factory()->create([
            'id'               => 6,
            'name'             => 'Divisional',
            'stock_base_point' => 50
        ]);
        StockPointsCalculationBases::factory()->create([
            'id'               => 7,
            'name'             => 'Regional',
            'stock_base_point' => 35
        ]);
        StockPointsCalculationBases::factory()->create([
            'id'               => 8,
            'name'             => 'VP',
            'stock_base_point' => 20
        ]);
        StockPointsCalculationBases::factory()->create([
            'id'               => 9,
            'name'             => 'Year Multiplier',
            'stock_base_point' => 1.50
        ]);
    }
}
