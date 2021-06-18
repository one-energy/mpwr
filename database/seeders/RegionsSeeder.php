<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use App\Enum\Role;
use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    public function run()
    {
        $department = Department::first();

        User::query()
            ->where('role', Role::REGION_MANAGER)
            ->each(function (User $user) use ($department) {
                /** @var Region $region */
                $region = Region::factory()->create(['department_id' => $department->id]);
                $region->managers()->attach($user);
            });
    }
}
