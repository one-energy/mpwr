<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(FinancersSeeder::class);
        $this->call(FinancingsSeeder::class);
        $this->call(TermsSeeder::class);
        // $this->call(DepartmentOne::class);
        // $this->call(DepartmentTwo::class);
        $this->call(BootstrapSeeder::class);
        // $this->call(DepartmentDevsquadSeeder::class);
        // $this->call(IncentivesTableSeeder::class);
        // $this->call(CustomersTableSeeder::class);
        // $this->call(TrainingTableSeeder::class);
        // $this->call(TrainingContentTableSeeder::class);
    }
}
