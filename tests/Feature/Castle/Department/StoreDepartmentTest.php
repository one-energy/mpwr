<?php

namespace Tests\Feature\Castle\Department;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
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
        $setter = User::factory()->create([
            'role' => 'Setter',
        ]);

        $department = Department::factory()->create([
            'department_manager_id' => $setter->id,
        ]);

        $setter->department_id = $department->id;
        $setter->save();

        $this->actingAs($setter)
            ->get(route('castle.departments.create'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        Department::factory()->create(['department_manager_id' => $admin->id]);

        $this->actingAs($admin)
            ->get(route('castle.departments.create'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.departments.create');
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_department()
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        $this->actingAs($admin)
            ->post(route('castle.departments.store'), [])
            ->assertSessionHasErrors([
                'name',
                'department_manager_id',
            ]);
    }
}
