<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegionTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_store_a_new_region()
    {
        $regionManager = User::factory()->create(['role' => 'Region Manager']);

        $departmentManager = User::factory()->create(['role' => 'Department Manager']);

        $department = Department::factory()->create(["department_manager_id" => $departmentManager->id]);

        $regionManager->department_id = $department->id;
        $regionManager->save();

        $data = [
            'name'              => 'Region',
            'region_manager_id' => $regionManager->id,
            "department_id"     => $department->id,
        ];

        $this->actingAs($regionManager);

        $response = $this->post(route('castle.regions.store'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('castle.regions.index'));
    }

    /** @test */
    public function it_should_list_all_regions()
    {
        $departmentManager = User::factory()->create(['role' => 'Department Manager']);
        $department        = Department::factory()->create(['department_manager_id' => $departmentManager->id]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $regionManager     = User::factory()->create([
            'role' => 'Region Manager',
            'department_id' => $department->id
        ]);
        $regions           = Region::factory()->count(6)->create([
            'region_manager_id' => $regionManager->id,
            'department_id' => $department->id,
        ]);
        $this->actingAs($departmentManager);
        $response = $this->get('castle/regions');

        $response->assertStatus(200)
            ->assertViewIs('castle.regions.index')
            ->assertViewHas('regions');

        foreach ($regions as $region) {
            $response->assertSee($region->name);
        }
    }

    /** @test */
    public function it_should_edit_an_region()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $regionManager     = User::factory()->create(['role' => 'Region Manager']);
        $region        = Region::factory()->create([
            'name'              => 'New Region',
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id
        ]);

        $data         = $region->toArray();
        $data['region_manager_id'] = $data['region_manager_id'];
        $updateRegion = array_merge($data, ['name' => 'Region Edited']);

        $this->actingAs($departmentManager);
        $response = $this->put(route('castle.regions.update', $region->id), $updateRegion);

        $response->assertStatus(302);

        $this->assertDatabaseHas(
            'Regions',
            [
                'id'   => $region->id,
                'name' => 'Region Edited'
            ]
        );
    }

    /** @test */
    public function it_should_destroy_an_region()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $regionManager = User::factory()->create(['role' => 'Region Manager']);
        $region        = Region::factory()->create([
            'region_manager_id' => $regionManager->id,
        ]);

        $this->actingAs($departmentManager);

        $response = $this->delete(route('castle.regions.destroy', $region->id));
        $deleted  = Region::where('id', $region->id)->get();

        $response->assertStatus(302);

        $this->assertNotNull($deleted);
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $departmentManager = $setter = User::factory()->create([
            "role" => "Department Manager"
        ]);
        $setter = User::factory()->create([
            "role" => "Setter"
        ]);

        $department = Department::factory()->create([
            "department_manager_id" => $departmentManager ->id
        ]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs($setter);

        $response = $this->get('castle/regions/create');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        $departmentManager = User::factory()->create([
            "role" => "Department Manager"
        ]);

        $department = Department::factory()->create([
            "department_manager_id" => $departmentManager->id
        ]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs($departmentManager);

        $response = $this->get('castle/regions/create');

        $response->assertStatus(200)
           ->assertViewIs('castle.regions.create');
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_region()
    {
        $departmentManager = User::factory()->create([
            "role" => "Department Manager"
        ]);

        $department = Department::factory()->create([
            "department_manager_id" => $departmentManager->id
        ]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs($departmentManager);

        $data = [
            'name'              => '',
            'region_manager_id' => '',
        ];

        $response = $this->post(route('castle.regions.store'), $data);
        $response->assertSessionHasErrors(
        [
            'name',
            'region_manager_id',
        ]);
    }

    /** @test */
    public function it_should_block_access_to_regions()
    {
        $officeManager = User::factory()->create(["role" => "Office Manager"]);
        $setter = User::factory()->create(["role" => "Setter"]);
        $salesRep = User::factory()->create(["role" => "Sales Rep"]);

        $this->actingAs($officeManager)
            ->get(route('castle.regions.index'))
            ->assertForbidden();

        $this->actingAs($setter)
            ->get(route('castle.regions.index'))
            ->assertForbidden();

        $this->actingAs($salesRep)
            ->get(route('castle.regions.index'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_allow_access_to_regions()
    {
        $owner = User::factory()->create(["role" => "Owner"]);
        $admin = User::factory()->create(["role" => "Admin"]);
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $regionManager = User::factory()->create(["role" => "Region Manager"]);

        $this->actingAs($owner)
            ->get(route('castle.regions.index'))
            ->assertSuccessful();

        $this->actingAs($admin)
            ->get(route('castle.regions.index'))
            ->assertSuccessful();

        $this->actingAs($departmentManager)
            ->get(route('castle.regions.index'))
            ->assertSuccessful();

        $this->actingAs($regionManager)
            ->get(route('castle.regions.index'))
            ->assertSuccessful();
    }
}
