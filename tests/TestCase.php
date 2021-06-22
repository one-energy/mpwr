<?php

namespace Tests;

use App\Enum\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createVP(): mixed
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($departmentManager->id);
        $departmentManager->update(['department_id' => $department->id]);

        return [$departmentManager, $department];
    }
}
