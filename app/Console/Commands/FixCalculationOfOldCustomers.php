<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixCalculationOfOldCustomers extends Command
{
    protected $signature = 'fix-margin-calculations';

    protected $description = 'This command will change margin value of each customers';

    public function handle()
    {

        DB::transaction(function () {
           Customer::get()->each(function ($customer) {
               $customer->margin = (float)$customer->epc - (float)$customer->sales_rep_fee - (float) $customer->setter_fee;
               $customer->save();
           });
        });
        
        return 0;
    }
}
