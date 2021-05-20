<?php

namespace Database\Seeders;

use App\Models\MultiplierOfYear;
use Illuminate\Database\Seeder;

class MultiplierOfYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MultiplierOfYear::factory()->create([
            'multiplier' => 1.50,
            'year'       => 2021
        ]);
    }
}
