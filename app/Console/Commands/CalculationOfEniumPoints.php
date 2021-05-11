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
        Term::find(4)->update([
            'noble_pay_dealer_fee' => 0.22,
            'rep_residual'         => 0.015,
            'amount'               => 480
        ]);
        Term::find(2)->update([
            'noble_pay_dealer_fee' => 0.24,
            'rep_residual'         => 0.015,
            'amount'               => 800
        ]);
        Term::find(3)->update([
            'noble_pay_dealer_fee' => 0.15,
            'rep_residual'         => 0.025,
            'amount'               => 480
        ]);
        Term::find(1)->update([
            'noble_pay_dealer_fee' => 0.19,
            'rep_residual'         => 0.025,
            'amount'               => 800
        ]);
        DB::transaction(function () {
            $users = User::withTrashed()->get();
            $users->each(function (User $user) {
                $user->customersOfSalesReps()->withTrashed()->get()->each(function ($customer) use ($user) {
                    if ($customer->term_id != null) {
                        $customerEniumPoint = $user->customersEniumPoints()->create([
                            'customer_id'          => $customer->id,
                            'points'               => $customer->term->amount > 0 ? round($customer->epc/$customer->term->amount) : 0,
                        ]);
                        if ($customer->deleted_at) {
                            $customerEniumPoint->delete();
                        }
                    }
                });
            }); 
        });
    
        return 0;
    }

}
