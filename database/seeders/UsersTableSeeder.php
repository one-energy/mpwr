<?php

namespace Database\Seeders;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'email'         => 'admin@devsquad.com',
            'role'          => Role::ADMIN,
            'department_id' => null,
        ]);

        User::factory()->create([
            'email'         => 'owner@devsquad.com',
            'role'          => Role::OWNER,
            'department_id' => null,
        ]);
    }
}
