<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Playground extends Command
{
    protected $signature = 'playground';

    protected $description = 'Command description';

    public function handle()
    {
        if (app()->environment('production')) {
            $this->error("Won't run in production.");

            return;
        }
    }
}
