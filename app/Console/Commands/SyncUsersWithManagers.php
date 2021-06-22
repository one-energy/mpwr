<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncUsersWithManagers extends Command
{
    protected $signature = 'sync:users-managers {--force}';

    protected $description = 'Sync users.[department|office|region]_id based on relation relations';

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
            User::query()
                ->whereNotIn('role', ['Admin', 'Owner'])
                ->where(function ($query) {
                    $query->whereNull('department_manager_id')
                        ->orWhereNull('office_manager_id')
                        ->orWhereNull('region_manager_id');
                })
                ->with([
                    'department'    => fn ($query) => $query->withTrashed(),
                    'office'        => fn ($query) => $query->withTrashed(),
                    'office.region' => fn ($query) => $query->withTrashed(),
                ])
                ->withTrashed()
                ->each(fn (User $user) => $this->syncManagers($user));
        });
    }

    private function syncManagers(User $user)
    {
        $data = $this->makeManagersArray($user);

        $user->update($data);
    }

    private function makeManagersArray(User $user)
    {
        $data = [
            'department_manager_id' => null,
            'office_manager_id'     => null,
            'region_manager_id'     => null,
        ];

        if ($user->office_id !== null) {
            $data = [
                'office_manager_id'     => $user->office->office_manager_id,
                'region_manager_id'     => $user->office->region->region_manager_id,
            ];
        }

        if ($user->department_id !== null) {
            $data['department_manager_id'] = $user->department->department_manager_id;
        }

        return $data;
    }
}
