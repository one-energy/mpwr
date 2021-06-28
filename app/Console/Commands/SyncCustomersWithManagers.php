<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCustomersWithManagers extends Command
{
    protected $signature = 'sync:customers-managers {--force}';

    protected $description = 'Sync customers.[department|office|region]_id based on relation in sales_rep_id';

    public function handle()
    {
        if (
            $this->option('force') === false &&
            !$this->confirm('Are you really sure? This will sync customers.[department|office|region]_id based on relation in sales_rep_id')
        ) {
            $this->info('Command aborted successfully!');

            return 0;
        }

        DB::transaction(function () {
            Customer::query()
                ->where(function ($query) {
                    $query->whereNull('department_manager_id')
                        ->orWhereNull('office_manager_id')
                        ->orWhereNull('region_manager_id');
                })
                ->whereHas('userSalesRep', fn ($query) => $query->whereNotIn('role', ['Admin', 'Owner']))
                ->with([
                    'userSalesRep'               => fn ($query) => $query->withTrashed(),
                    'userSalesRep.department'    => fn ($query) => $query->withTrashed(),
                    'userSalesRep.office'        => fn ($query) => $query->withTrashed(),
                    'userSalesRep.office.region' => fn ($query) => $query->withTrashed(),
                ])
                ->withTrashed()
                ->each(fn (Customer $customer) => $this->syncManagers($customer));
        });
    }

    private function syncManagers(Customer $customer)
    {
        $data = $this->makeManagersArray($customer);

        $customer->update($data);
    }

    private function makeManagersArray(Customer $customer)
    {
        $data = [
            'department_manager_id' => null,
            'office_manager_id'     => null,
            'region_manager_id'     => null,
        ];

        /** @var User $manager */
        $manager = $customer->userSalesRep;

        if ($manager->office_id !== null) {
            $data = [
                'office_manager_id'     => $manager->office->office_manager_id,
                'region_manager_id'     => $manager->office->region->region_manager_id,
            ];
        }

        if ($manager->department_id !== null) {
            $data['department_manager_id'] = $manager->department->department_manager_id;
        }

        return $data;
    }
}
