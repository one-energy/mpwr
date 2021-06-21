<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    public function run()
    {
        $department = Department::first();

        User::query()
            ->where('role', 'Region Manager')
            ->each(function (User $user) use ($department) {
                Region::factory()->create([
                    'department_id'     => $department->id,
                    'region_manager_id' => $user->id,
                ]);
            });
    }
}
