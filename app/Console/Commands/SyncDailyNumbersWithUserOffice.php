<?php

namespace App\Console\Commands;

use App\Models\DailyNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncDailyNumbersWithUserOffice extends Command
{
    protected $signature = 'daily-numbers:sync';

    protected $description = 'Sync the daily_numbers.office_id with the user.office_id';

    public function handle()
    {
        if (!$this->confirm('Are you really sure? This will sync daily_numbers.office_id with the user.office_id')) {
            $this->info('Command aborted successfully!');

            return 0;
        }

        DB::transaction(function () {
            DailyNumber::with(['user' => fn($query) => $query->withTrashed()])->chunk(500, function ($dailyNumbers) {
                $dailyNumbers->each(function (DailyNumber $dailyNumber) {
                    $dailyNumber->update(['office_id' => $dailyNumber->user->office_id]);
                });
            });
        });

        $this->info('Command run successfully!');

        return 0;
    }
}
