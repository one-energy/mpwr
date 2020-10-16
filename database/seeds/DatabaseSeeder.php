<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        // $this->call(DepartmentOne::class);
        // $this->call(DepartmentTwo::class);
        $this->call(BootstrapSeeder::class);
        $this->call(IncentivesTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(TrainingTableSeeder::class);
        $this->call(TrainingContentTableSeeder::class);
    }
}
