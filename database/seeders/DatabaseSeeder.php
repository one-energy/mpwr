<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PhotosSeeder::class,
            UsersSeeder::class,
            FinancersSeeder::class,
            FinancingsSeeder::class,
            TermsSeeder::class,
            DepartmentsSeeder::class,
            RegionsSeeder::class,
            OfficesSeeder::class,
            DailyNumbersSeeder::class,
            CustomersSeeder::class,
            MultiplierOfYearSeeder::class,
            UserEniumPointLevelSeeder::class,
            StockPointsCalculationBasesSeeder::class
        ]);
    }
}
