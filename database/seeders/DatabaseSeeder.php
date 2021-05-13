<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PhotosSeeder::class,
            UsersTableSeeder::class,
            FinancersSeeder::class,
            TermsSeeder::class,
            DepartmentsSeeder::class,
            RegionsSeeder::class,
            OfficesSeeder::class,
            DailyNumbersSeeder::class
        ]);
    }
}
