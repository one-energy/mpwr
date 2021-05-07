<?php

namespace App\Console\Commands;

use App\Models\Term;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculationOfEniumPoints extends Command
{
    protected $signature = 'calculate:customer-enium-points';

    protected $description = 'This command will create a enium points values for each customer of each users';

    public function handle()
    {
        DB::transaction(function () {
            Term::find(1)->update([
                'noble_pay_dealer_fee' => 0.015,
                'rep_residual'         => 0.025,
                'amount'               => 480
            ]);
            Term::find(2)->update([
                'noble_pay_dealer_fee' => 0.22,
                'rep_residual'         => 0.015,
                'amount'               => 800
            ]);
            Term::find(3)->update([
                'noble_pay_dealer_fee' => 0.015,
                'rep_residual'         => 0.025,
                'amount'               => 480
            ]);
            Term::find(4)->update([
                'noble_pay_dealer_fee' => 0.22,
                'rep_residual'         => 0.015,
                'amount'               => 800
            ]);
            $users = User::withTrashed()->get();
            $users->each(function (User $user) {
                $user->customersOfSalesReps()->withTrashed()->get()->each(function ($customer) use ($user) {
                    if ($customer->term_id != null) {
                        dd($customer->epc);
                        $user->customersEniumPoints()->create([
                            'customer_id'          => $customer->id,
                            'enium_points_of_sale' => $customer->epc/$customer->term->amount,
                            'deleted_at'           => $customer->deleted_at
                        ]);
                    }
                });
            }); 
        });
    
        return 0;
    }

}
