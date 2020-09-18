<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        factory(Customer::class, 20)->create();
    }
}
