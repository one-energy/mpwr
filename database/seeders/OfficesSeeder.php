<?php

namespace Database\Seeders;

use App\Enum\Role;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;

class OfficesSeeder extends Seeder
{
    public function run()
    {
        Region::with('department')->get()->each(fn(Region $region) => $this->createOfficeManagers($region));
    }

    private function createOfficeManagers(Region $region)
    {
        User::factory()
            ->times(3)
            ->create([
                'role'          => Role::OFFICE_MANAGER,
                'department_id' => $region->department_id,
            ])
            ->each(fn(User $user) => $this->createOffice($region, $user));
    }

    private function createOffice(Region $region, User $user)
    {
        /** @var Office $office */
        $office = Office::factory()->create([
            'office_manager_id' => $user->id,
            'region_id'         => $region->id,
        ]);

        User::query()
            ->whereIn('id', [$user->id, $region->region_manager_id])
            ->update([
                'office_id'             => $office->id,
                'office_manager_id'     => $user->id,
                'region_manager_id'     => $region->region_manager_id,
                'department_manager_id' => $region->department->department_manager_id,
            ]);

        $this->createUsers($office, $region);
    }

    private function createUsers(Office $office, Region $region)
    {
        User::factory()->times(2)->create([
            'role'                  => Role::SETTER,
            'office_id'             => $office->id,
            'department_id'         => $region->department_id,
            'department_manager_id' => $region->department->department_manager_id,
            'office_manager_id'     => $office->office_manager_id,
            'region_manager_id'     => $region->region_manager_id,
        ]);
        User::factory()->times(2)->create([
            'role'                  => Role::SALES_REP,
            'office_id'             => $office->id,
            'department_id'         => $region->department_id,
            'department_manager_id' => $region->department->department_manager_id,
            'office_manager_id'     => $office->office_manager_id,
            'region_manager_id'     => $region->region_manager_id,
        ]);
    }
}
