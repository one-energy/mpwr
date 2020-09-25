<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_store_a_new_department()
    {
        $departmentManager = factory(User::class)->create(['role' => 'Department Manager']);

        $data = [
            'name'              => 'Department',
            'department_manager_id' => $departmentManager->id,
        ];

        $response = $this->post(route('castle.departments.store'), $data);

        $created = Department::where('name', $data['name'])->first();

        $response->assertStatus(302)
            ->assertRedirect(route('castle.departments.edit', $created));
    }

    /** @test */
    public function it_should_list_all_departments()
    {
        $departmentManager = factory(User::class)->create(['role' => 'Department Manager']);
        $departments       = factory(Department::class, 6)->create([
            'department_manager_id' => $departmentManager->id,
        ]);

        $response = $this->get('castle/departments');

        $response->assertStatus(200)
            ->assertViewIs('castle.departments.index')
            ->assertViewHas('departments');

        foreach ($departments as $department) {
            $response->assertSee($department->name);
        }
    }

    /** @test */
    public function it_should_edit_an_department()
    {
        $departmentManager = factory(User::class)->create(['role' => 'Department Manager']);
        $department        = factory(Department::class)->create([
            'name'              => 'New Department',
            'department_manager_id' => $departmentManager->id,
        ]);
        $data         = $department->toArray();
        $data['department_manager_id'] = $data['department_manager_id'];
        $updateDepartment = array_merge($data, ['name' => 'Department Edited']);

        $response = $this->put(route('castle.departments.update', $department->id), $updateDepartment);

        $response->assertStatus(302);

        $this->assertDatabaseHas(
            'Departments',
            [
                'id'   => $department->id,
                'name' => 'Department Edited'
            ]
        );
    }

    /** @test */
    public function it_should_destroy_an_department()
    {
        $departmentManager = factory(User::class)->create(['role' => 'Department Manager']);
        $department        = factory(Department::class)->create([
            'department_manager_id' => $departmentManager->id,
        ]);

        $response = $this->delete(route('castle.departments.destroy', $department->id));
        $deleted  = Department::where('id', $department->id)->get();

        $response->assertStatus(302);

        $this->assertNotNull($deleted);
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['master' => false]));

        $response = $this->get('castle/departments/create');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
       $response = $this->get('castle/departments/create');
       
       $response->assertStatus(200)
           ->assertViewIs('castle.departments.create');
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_department()
    {
        $data = [
            'name'              => '',
            'department_manager_id' => '',
        ];

        $response = $this->post(route('castle.departments.store'), $data);
        $response->assertSessionHasErrors(
        [
            'name',
            'department_manager_id',
        ]);
    }
}   