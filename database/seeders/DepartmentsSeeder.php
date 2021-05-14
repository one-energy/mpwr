<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use App\Role\Role;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    public function run()
    {
        /** @var User $manager */
        $manager = User::where('role', Role::DEPARTMENT_MANAGER)->first();

        /** @var Department $department */
        $department = Department::factory()->create(['name' => 'California Renewable Energy']);
        $department->trainingPageSections()->create(['title' => 'Training Page']);
        $department->managers()->attach($manager);

        User::query()
            ->whereNotIn('role', [Role::ADMIN, Role::OWNER])
            ->update(['department_id' => $department->id]);

        Department::factory()
            ->times(28)
            ->create()
            ->each(
                fn(Department $department) => $department->trainingPageSections()->create(['title' => 'Training Page'])
            );
    }
}
