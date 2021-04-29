<?php

namespace Database\Seeders;

use App\Models\User;
use App\Role\Role;
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
            'role'          => 'Owner',
            'department_id' => null,
        ]);

        User::factory()->create([
            'email'         => 'vp@devsquad.com',
            'role'          => 'Department Manager',
            'department_id' => null,
        ]);

        collect()->times(3)->each(function ($number) {
            User::factory()->create([
                'email'         => sprintf('rm%s@devsquad.com', str_pad($number, 2, '0', STR_PAD_LEFT)),
                'role'          => 'Region Manager',
                'department_id' => null,
            ]);
        });

        collect()->times(3)->each(function ($number) {
            User::factory()->create([
                'email'         => sprintf('om%s@devsquad.com', str_pad($number, 2, '0', STR_PAD_LEFT)),
                'role'          => 'Office Manager',
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
                'role'          => 'Setter',
                'department_id' => null,
            ]);
        });
    }
}
