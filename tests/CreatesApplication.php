<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

trait CreatesApplication
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();
        Hash::setRounds(5);

        return $app;
    }
}
