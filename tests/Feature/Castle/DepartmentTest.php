<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_store_a_new_department()
    {
        $admin = factory(User::class)->create(["role" => "Admin"]);

        $data = [
            'name'              => 'Department',
            'department_manager_id' => $admin->id,
        ];

        $this->actingAs($admin);

        $response = $this->post(route('castle.departments.store'), $data);

        $created = Department::where('name', $data['name'])->first();

        $response->assertStatus(302)
            ->assertRedirect(route('castle.departments.index'));
    }

    /** @test */
    public function it_should_list_all_departments()
    {
        $admin = factory(User::class)->create(['role' => 'Admin']);

        $departmentManagerOne = factory(User::class)->create(['role' => 'Department Manager']);
        $departmentManagerTwo = factory(User::class)->create(['role' => 'Department Manager']);

        $departmentOne       = factory(Department::class)->create([
            'department_manager_id' => $departmentManagerOne->id,
        ]);

        $departmentTwo       = factory(Department::class)->create([
            'department_manager_id' => $departmentManagerTwo->id,
        ]);

        $departmentManagerOne->department_id = $departmentOne->id;
        $departmentManagerOne->save();

        $departmentManagerTwo->department_id = $departmentTwo->id;
        $departmentManagerTwo->save();

        $this->actingAs($admin);

        $response = $this->get('castle/departments');

        $response->assertStatus(200)
            ->assertViewIs('castle.departments.index');
        
        $response->assertSee($departmentManagerOne->name);
        $response->assertSee($departmentManagerTwo->name);
    }

    /** @test */
    public function it_should_edit_an_department()
    {
        $admin = factory(User::class)->create(["role" => "Admin"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $admin->id]);

        $data         = $department->toArray();
        $data['department_manager_id'] = $data['department_manager_id'];
        $updateDepartment = array_merge($data, ['name' => 'Department Edited']);

        $this->actingAs($admin);

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
        $admin = factory(User::class)->create(["role" => "Admin"]);
        $department        = factory(Department::class)->create([
            'department_manager_id' => $admin->id,
        ]);

        $admin->department_id = $department->id;
        $admin->save();
        
        $this->actingAs($admin);
        
        $response = $this->delete(route('castle.departments.destroy', $department->id));
        $deleted  = Department::where('id', $department->id)->get();

        $response->assertStatus(302);

        $this->assertNotNull($deleted);
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $setter = factory(User::class)->create([
            "role" => "Setter"
        ]);

        $department = factory(Department::class)->create([
            "department_manager_id" => $setter->id
        ]);

        $setter->department_id = $department->id;
        $setter->save();

        $this->actingAs($setter);

        $response = $this->get('castle/departments/create');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        $admin = factory(User::class)->create(["role" => "Admin"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $admin->id]);

        $this->actingAs($admin);
        $response = $this->get('castle/departments/create');
       
        $response->assertStatus(200)
           ->assertViewIs('castle.departments.create');
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_department()
    {
        $admin = factory(User::class)->create(["role" => "Admin"]);

        $data = [
            'name'              => '',
            'department_manager_id' => '',
        ];

        $this->actingAs($admin);
        $response = $this->post(route('castle.departments.store'), $data);
        $response->assertSessionHasErrors(
        [
            'name',
            'department_manager_id',
        ]);
    }

    /** @test */
    public function it_should_block_access()
    {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $regionManager = factory(User::class)->create(["role" => "Region Manager"]);
        $officeManager = factory(User::class)->create(["role" => "Office Manager"]);
        $setter = factory(User::class)->create(["role" => "Setter"]);
        $salesRep = factory(User::class)->create(["role" => "Sales Rep"]);

        $this->actingAs($regionManager)
            ->get(route('castle.departments.index'))
            ->assertForbidden();
    }
}   