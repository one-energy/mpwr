<?php

namespace Database\Seeders;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'email'         => 'admin@devsquad.com',
            'first_name'    => 'DevSquad',
            'last_name'     => 'Admin',
            'role'          => Role::ADMIN,
            'department_id' => null,
        ]);

        User::factory()->create([
            'email'         => 'owner@devsquad.com',
            'first_name'    => 'DevSquad',
            'last_name'     => 'Owner',
            'role'          => Role::OWNER,
            'department_id' => null,
        ]);
    }
}
