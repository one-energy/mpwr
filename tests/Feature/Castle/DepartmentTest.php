<?php

namespace Tests\Feature\Castle;

use App\Http\Livewire\Castle\Departments;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DepartmentTest extends TestCase
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
        $admin = User::factory()->create(["role" => "Admin"]);

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
        $admin = User::factory()->create(['role' => 'Admin']);

        $departmentManagerOne = User::factory()->create(['role' => 'Department Manager']);
        $departmentManagerTwo = User::factory()->create(['role' => 'Department Manager']);

        $departmentOne       = Department::factory()->create([
            'department_manager_id' => $departmentManagerOne->id,
        ]);

        $departmentTwo       = Department::factory()->create([
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
        $admin = User::factory()->create(["role" => "Admin"]);
        $department        = Department::factory()->create(["department_manager_id" => $admin->id]);

        $data         = $department->toArray();
        $data['department_manager_id'] = $data['department_manager_id'];
        $updateDepartment = array_merge($data, ['name' => 'Department Edited']);

        $this->actingAs($admin);

        $response = $this->put(route('castle.departments.update', $department->id), $updateDepartment);

        $response->assertStatus(302);

        $this->assertDatabaseHas(
            'departments',
            [
                'id'   => $department->id,
                'name' => 'Department Edited'
            ]
        );
    }

    /** @test */
    public function it_should_destroy_an_department()
    {
        $admin = User::factory()->create(["role" => "Admin"]);
        $department        = Department::factory()->create([
            'department_manager_id' => $admin->id,
        ]);

        $admin->department_id = $department->id;
        $admin->save();

        $this->actingAs($admin);

        Livewire::test(Departments::class)->call('setDeletingDepartment', $department)
        ->set('deletingName', $department->name)
        ->call('destroy')
        ->assertDontSee($department->name);
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $setter = User::factory()->create([
            "role" => "Setter"
        ]);

        $department = Department::factory()->create([
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
        $admin = User::factory()->create(["role" => "Admin"]);
        $department        = Department::factory()->create(["department_manager_id" => $admin->id]);

        $this->actingAs($admin);
        $response = $this->get('castle/departments/create');

        $response->assertStatus(200)
           ->assertViewIs('castle.departments.create');
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_department()
    {
        $admin = User::factory()->create(["role" => "Admin"]);

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
    public function it_should_block_access_to_department()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $regionManager = User::factory()->create(["role" => "Region Manager"]);
        $officeManager = User::factory()->create(["role" => "Office Manager"]);
        $setter = User::factory()->create(["role" => "Setter"]);
        $salesRep = User::factory()->create(["role" => "Sales Rep"]);

        $this->actingAs($departmentManager)
            ->get(route('castle.departments.index'))
            ->assertForbidden();

        $this->actingAs($regionManager)
            ->get(route('castle.departments.index'))
            ->assertForbidden();

        $this->actingAs($officeManager)
            ->get(route('castle.departments.index'))
            ->assertForbidden();

        $this->actingAs($setter)
            ->get(route('castle.departments.index'))
            ->assertForbidden();

        $this->actingAs($salesRep)
            ->get(route('castle.departments.index'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_allow_access_to_department()
    {
        $owner = User::factory()->create(["role" => "Owner"]);
        $admin = User::factory()->create(["role" => "Admin"]);

        $this->actingAs($owner)
            ->get(route('castle.departments.index'))
            ->assertSuccessful();

        $this->actingAs($admin)
            ->get(route('castle.departments.index'))
            ->assertSuccessful();
    }

}
