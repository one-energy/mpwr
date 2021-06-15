<?php

namespace App\Console\Commands;

use App\Models\DailyNumber;
use Illuminate\Console\Command;

class SyncDailyNumbersUpdate extends Command
{
    protected $signature = 'sync-daily-numbers';

    protected $description = 'This command will get the old numbers and insert on new properties';

    public function handle()
    {
        $dailyNumbers = DailyNumber::withTrashed()->get();
        $dailyNumbers->map(function($number) {
            if ($number->hours_knocked == 0) {
                $number->hours_knocked = $number->hours;
                $number->save();
            }
        });
        return 0;
    }
}
