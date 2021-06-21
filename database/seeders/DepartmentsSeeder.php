<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    public function run()
    {
        /** @var User $manager */
        $manager = User::where('role', 'Department Manager')->first();

        $department = Department::factory()->create([
            'name'                  => 'California Renewable Energy',
            'department_manager_id' => $manager->id
        ]);
        $department->trainingPageSections()->create(['title' => 'Training Page']);

        User::query()
            ->whereNotIn('role', ['Admin', 'Owner'])
            ->update(['department_id' => $department->id]);

        Department::factory()
            ->times(28)
            ->create()
            ->each(
                fn(Department $department) => $department->trainingPageSections()->create(['title' => 'Training Page'])
            );
    }
}
