<?php

namespace Tests\Feature\Castle\Department;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateDepartmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_edit_an_department()
    {
        $admin      = User::factory()->create(['role' => 'Admin']);
        $department = Department::factory()->create(['department_manager_id' => $admin->id]);

        $data             = $department->toArray();
        $updateDepartment = array_merge($data, ['name' => 'Department Edited']);

        $this->actingAs($admin)
            ->put(route('castle.departments.update', $department->id), $updateDepartment)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('departments', [
            'id'   => $department->id,
            'name' => 'Department Edited',
        ]);
    }
}
