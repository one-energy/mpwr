<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
use App\Models\Incentive;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageIncentiveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_incentives()
    {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $incentives = factory(Incentive::class, 6)->create([
            "department_id" => $department->id
        ]);

        $this->actingAs($departmentManager);

        $response = $this->get(route('castle.incentives.index'));

        $response->assertStatus(200)
            ->assertViewIs('castle.incentives.index');

        foreach ($incentives as $incentive) {
            $response->assertSee($incentive->name);
        }
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $setter = factory(User::class)->create([
            "role" => "setter"
        ]);

        $department = factory(Department::class)->create([
            "department_manager_id" => $setter->id
        ]);

        $setter->department_id = $department->id;
        $setter->save();

        $this->actingAs($setter);

        $response = $this->get(route('castle.incentives.create'));

        $response->assertStatus(403);
    }

     /** @test */
     public function it_should_show_the_create_form_for_top_level_roles()
     {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs($departmentManager);

        $response = $this->get(route('castle.incentives.create'));

        $response->assertStatus(200)
            ->assertViewIs('castle.incentives.create');
     }

    /** @test */
    public function it_should_store_a_new_incentive()
    {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $data = [
            'number_installs'   => 48,
            'name'              => 'Incentive',
            'installs_needed'   => 67,
            'kw_needed'         => 100,
            'department_id'     => $department->id
        ];

        $this->actingAs($departmentManager);

        $response = $this->post(route('castle.incentives.store'), $data);

        $created = Incentive::where('name', $data['name'])->first();

        $response->assertStatus(302)
            ->assertRedirect(route('castle.incentives.index'));
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_incentive()
    {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $data = [
            'number_installs'   => '',
            'name'              => '',
            'installs_needed'   => '',
            'kw_needed'         => '',
        ];

        $this->actingAs($departmentManager);

        $response = $this->post(route('castle.incentives.store'), $data);
        $response->assertSessionHasErrors(
        [
            'number_installs',
            'name',
            'installs_needed',
            'kw_needed',
        ]);
    }

    /** @test */
    public function it_should_show_the_edit_form_for_top_level_roles()
    {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $incentive = factory(Incentive::class)->create();

        $this->actingAs($departmentManager);

        $response = $this->get(route('castle.incentives.edit', $incentive->id));

        $response->assertStatus(200)
            ->assertViewIs('castle.incentives.edit');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Setter']));

        $incentive = factory(Incentive::class)->create();

        $response = $this->get(route('castle.incentives.edit', $incentive->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_update_an_incentive()
    {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $incentive       = factory(Incentive::class)->create([
            'name' => 'Incentive',
            'department_id' => $department->id
        ]);
        $data            = $incentive->toArray();
        $updateIncentive = array_merge($data, ['name' => 'Incentive Edited']);

        $this->actingAs($departmentManager);

        $response = $this->put(route('castle.incentives.update', $incentive->id), $updateIncentive);

        $response->assertStatus(302);

        $this->assertDatabaseHas('incentives',
        [
            'id'   => $incentive->id,
            'name' => 'Incentive Edited'
        ]);
    }

    /** @test */
    public function it_should_destroy_an_incentive()
    {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $incentive = factory(Incentive::class)->create();

        $this->actingAs($departmentManager);

        $response = $this->delete(route('castle.incentives.destroy', $incentive->id));
        $deleted  = Incentive::where('id', $incentive->id)->get();

        $response->assertStatus(302);

        $this->assertNotNull($deleted);
    }

    /** @test */
    public function it_should_block_access_to_incentives()
    {
        $regionManager = factory(User::class)->create(["role" => "Region Manager"]);
        $officeManager = factory(User::class)->create(["role" => "Office Manager"]);
        $setter = factory(User::class)->create(["role" => "Setter"]);
        $salesRep = factory(User::class)->create(["role" => "Sales Rep"]);

        $this->actingAs($regionManager)
            ->get(route('castle.incentives.index'))
            ->assertForbidden();

        $this->actingAs($officeManager)
            ->get(route('castle.incentives.index'))
            ->assertForbidden();

        $this->actingAs($setter)
            ->get(route('castle.incentives.index'))
            ->assertForbidden();

        $this->actingAs($salesRep)
            ->get(route('castle.incentives.index'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_allow_access_to_incentives()
    {
        $owner = factory(User::class)->create(["role" => "Owner"]);
        $admin = factory(User::class)->create(["role" => "Admin"]);
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);

        $this->actingAs($owner)
            ->get(route('castle.incentives.index'))
            ->assertSuccessful();

        $this->actingAs($admin)
            ->get(route('castle.incentives.index'))
            ->assertSuccessful();

        $this->actingAs($departmentManager)
            ->get(route('castle.incentives.index'))
            ->assertSuccessful();
    }
}
