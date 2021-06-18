<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enum\Role;
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

        collect()->times(10)->each(function ($number) {
            User::factory()->create([
                'email'         => sprintf('vp%s@devsquad.com', str_pad($number, 2, '0', STR_PAD_LEFT)),
                'role'          => Role::DEPARTMENT_MANAGER,
                'department_id' => null,
            ]);
        });

        collect()->times(3)->each(function ($number) {
            User::factory()->create([
                'email'         => sprintf('rm%s@devsquad.com', str_pad($number, 2, '0', STR_PAD_LEFT)),
                'role'          => Role::REGION_MANAGER,
                'department_id' => null,
            ]);
        });

        collect()->times(3)->each(function ($number) {
            User::factory()->create([
                'email'         => sprintf('om%s@devsquad.com', str_pad($number, 2, '0', STR_PAD_LEFT)),
                'role'          => Role::OFFICE_MANAGER,
                'department_id' => null,
            ]);
        });

        collect()->times(30)->each(function ($number) {
            User::factory()->create([
                'email'         => sprintf('sr%s@devsquad.com', str_pad($number, 2, '0', STR_PAD_LEFT)),
                'role'          => 'Sales Rep',
                'department_id' => null,
            ]);
        });

        collect()->times(30)->each(function ($number) {
            User::factory()->create([
                'email'         => sprintf('st%s@devsquad.com', str_pad($number, 2, '0', STR_PAD_LEFT)),
                'role'          => Role::SETTER,
                'department_id' => null,
            ]);
        });
    }
}
