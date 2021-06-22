<?php

namespace Tests\Feature\Castle\Department;

use App\Enum\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreDepartmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

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
    public function it_should_detach_the_managed_departments_from_the_user_if_he_will_manage_other_department()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var User $mary */
        $mary = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($mary->id);
        $mary->update(['department_id' => $department->id]);

        $data = $this->makeData(['department_manager_ids' => [$mary->id]]);

        $this->assertSame($department->id, $mary->department_id);

        $this
            ->actingAs($john)
            ->post(route('castle.departments.store'), $data)
            ->assertSessionHasNoErrors();

        $createdDepartment = Department::where('name', $data['name'])->first();
        $mary->refresh();

        $this->assertCount(1, $mary->managedDepartments);
        $this->assertNotEquals($department->id, $mary->department_id);
        $this->assertSame($createdDepartment->id, $mary->department_id);
        $this->assertSame($createdDepartment->name, $mary->managedDepartments->first()->name);
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

        $data = $this->makeData([
            'department_manager_ids' => [
                User::factory()->create(['role' => Role::SETTER])->id,
                User::factory()->create(['role' => Role::OFFICE_MANAGER])->id,
            ],
        ]);

        $this
            ->actingAs($john)
            ->post(route('castle.departments.store'), $data)
            ->assertSessionHasErrors('department_manager_ids.0')
            ->assertSessionHasErrors('department_manager_ids.1');
    }

    /** @test */
    public function it_should_store_a_new_department()
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        $data = [
            'name'                  => 'Department',
            'department_manager_id' => $admin->id,
        ];

        $this->actingAs($admin)
            ->post(route('castle.departments.store'), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('castle.departments.index'));
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $setter = User::factory()->create(['role' => Role::SETTER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($setter->id);

        $this->actingAs($setter)
            ->get(route('castle.departments.create'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($admin->id);

        $this->actingAs($admin)
            ->get(route('castle.departments.create'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.departments.create');
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_department()
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($admin)
            ->post(route('castle.departments.store'), [])
            ->assertSessionHasErrors(['name']);
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
