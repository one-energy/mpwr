<?php

namespace Database\Seeders;

use App\Enum\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    public function run()
    {
        User::factory()
            ->times(5)
            ->create(['role' => Role::DEPARTMENT_MANAGER])
            ->each(fn(User $user) => $this->createDepartment($user));
    }

    private function createDepartment(User $user)
    {
        /** @var Department $department */
        $department = Department::factory()->create(['department_manager_id' => $user->id]);

        $department->trainingPageSections()->create(['title' => 'Training Page']);
        $user->update(['department_id' => $department->id]);
    }
}
