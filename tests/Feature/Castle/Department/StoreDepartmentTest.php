<?php

namespace Tests\Feature\Castle\Department;

use App\Models\Department;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreDepartmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_store_a_department()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $data = $this->makeData();

        $this->assertDatabaseCount('departments', 0);
        $this->assertDatabaseCount('user_managed_departments', 0);

        $this
            ->actingAs($john)
            ->post(route('castle.departments.store'), $data)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('departments', 1);
        $this->assertDatabaseCount('user_managed_departments', 2);

        /** @var Department $createdDepartment */
        $createdDepartment = Department::where('name', $data['name'])->first();

        $this->assertDatabaseHas('departments', [
            'name' => $createdDepartment->name,
        ]);

        $this->assertSame($data['department_manager_ids'][0], $createdDepartment->managers[0]->id);
        $this->assertSame($data['department_manager_ids'][1], $createdDepartment->managers[1]->id);
    }

    /** @test */
    public function it_should_require_name()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $data = $this->makeData(['name' => null]);

        $this
            ->actingAs($john)
            ->post(route('castle.departments.store'), $data)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_should_require_department_manager_ids()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $data = $this->makeData(['department_manager_ids' => []]);

        $this
            ->actingAs($john)
            ->post(route('castle.departments.store'), $data)
            ->assertSessionHasErrors('department_manager_ids');
    }

    /** @test */
    public function it_should_require_valid_department_manager_ids()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $data = $this->makeData(['department_manager_ids' => [Str::random(3), Str::random(3)]]);

        $this
            ->actingAs($john)
            ->post(route('castle.departments.store'), $data)
            ->assertSessionHasErrors('department_manager_ids.0')
            ->assertSessionHasErrors('department_manager_ids.1');
    }

    /** @test */
    public function it_should_prevent_department_manager_ids_that_arent_from_users_that_have_department_manager_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $data = $this->makeData(['department_manager_ids' => [
            User::factory()->create(['role' => Role::SETTER])->id,
            User::factory()->create(['role' => Role::OFFICE_MANAGER])->id,
        ]]);

        $this
            ->actingAs($john)
            ->post(route('castle.departments.store'), $data)
            ->assertSessionHasErrors('department_manager_ids.0')
            ->assertSessionHasErrors('department_manager_ids.1');
    }

    private function makeData(array $attributes = []): array
    {
        return array_merge([
            'name'                   => Str::random(),
            'department_manager_ids' => User::factory()
                ->times(2)
                ->create(['role' => Role::DEPARTMENT_MANAGER])
                ->pluck('id')
                ->toArray(),
        ], $attributes);
    }
}
