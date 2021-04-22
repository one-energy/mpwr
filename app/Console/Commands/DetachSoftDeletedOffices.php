<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DetachSoftDeletedOffices extends Command
{
    protected $signature = 'detach-soft-deleted-offices';

    protected $description = 'Loop through all users and check if the office was soft deleted. If so, detach the office_id';

    public function handle()
    {
        DB::transaction(function () {
            User::whereHas('office', function ($query) {
                $query->withTrashed()->whereNotNull('deleted_at');
            })
                ->delete();
        });

        return 0;
    }
}
