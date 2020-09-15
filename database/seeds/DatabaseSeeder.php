<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(TrainingTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(IncentivesTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
    }
}
