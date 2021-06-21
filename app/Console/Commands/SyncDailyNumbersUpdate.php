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
        $size = DailyNumber::withTrashed()
            ->orWhere('hours_knocked', 0)
            ->orWhere('sats', 0)
            ->count();
        $bar = $this->output->createProgressBar($size);

        $this->info('Updating daily numbers');
        $this->newLine();

        DailyNumber::withTrashed()
            ->orWhere('hours_knocked', 0)
            ->orWhere('sats', 0)
            ->chunk(200, function($dailyNumbers) use ($bar) {
                $dailyNumbers->each(function ($number) use ($bar) {
                    
                    $bar->advance();
                    if ($number->hours_knocked == 0) {
                        $number->hours_knocked = $number->hours;
                    }
        
                    if ($number->sats == 0) {
                        $number->sats = $number->set_sits;
                    }
        
                    $number->save();

                });
            });
        return 0;
    }
}
