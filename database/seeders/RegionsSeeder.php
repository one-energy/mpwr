<?php

namespace Database\Seeders;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    public function run()
    {
        Department::all()->each(fn(Department $department) => $this->createRegionManagers($department));
    }

    private function createRegionManagers(Department $department)
    {
        User::factory()
            ->times(3)
            ->create([
                'role'          => Role::REGION_MANAGER,
                'department_id' => $department->id,
            ])
            ->each(fn(User $user) => $this->createRegion($department, $user));
    }

    private function createRegion(Department $department, User $user)
    {
        /** @var Region $region */
        $region = Region::factory()->create(['department_id' => $department->id]);
        $region->managers()->attach($user->id);
    }
}
