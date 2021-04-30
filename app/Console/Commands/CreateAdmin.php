<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'create:admin';

    protected $description = 'Create an User with Admin Role';

    public function handle()
    {
        User::factory()->create([
            'role'          => 'Admin',
            'email'         => 'dev@devsquad.com',
            'password'      => bcrypt('secret'),
            'department_id' => null,
            'master'        => true,
        ]);

        User::factory()->create([
            'role'          => 'Admin',
            'email'         => 'admin@devsquad.com',
            'password'      => bcrypt('secret'),
            'department_id' => null,
            'master'        => true,
        ]);

        User::factory()->create([
            'role'          => 'Owner',
            'email'         => 'owner@devsquad.com',
            'password'      => bcrypt('secret'),
            'department_id' => null,
            'master'        => true,
        ]);

        return 0;
    }
}
