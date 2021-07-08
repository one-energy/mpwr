<?php

namespace Tests\Feature\Castle\Region;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GetRegionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $departmentManager;

    private User $regionManager;

    private User $officeManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin             = User::factory()->create(['role' => Role::ADMIN]);
        $this->departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $this->regionManager     = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $this->officeManager     = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
    }

    /** @test */
    public function it_should_return_all_regions_when_auth_user_has_admin_role()
    {
        $department = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);
        /** @var Collection $regions */
        $regions = Region::factory()->times(10)->create([
            'department_id'     => $department->id,
            'region_manager_id' => $this->regionManager->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson(route('getRegions', ['department' => $department]))
            ->assertSuccessful()
            ->assertOk();

        $regions->each(function (Region $region, $index) use ($response) {
            $key = sprintf('%s.name', $index);

            $response->assertJson(fn(AssertableJson $json) => $json->where($key, $region->name));
        });
    }

    /** @test */
    public function it_should_return_all_regions_by_department_id_when_auth_user_has_department_manager_role()
    {
        $department01 = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);
        $department02 = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);

        /** @var Collection $regions */
        $regions = Region::factory()->times(10)->create([
            'department_id'     => $department01->id,
            'region_manager_id' => $this->regionManager->id,
        ]);

        Region::factory()->times(2)->create([
            'department_id'     => $department02->id,
            'region_manager_id' => $this->regionManager->id,
        ]);

        $response = $this->actingAs($this->departmentManager)
            ->getJson(route('getRegions', ['department' => $department01]))
            ->assertSuccessful()
            ->assertOk();

        $regions->each(function (Region $region, $index) use ($response) {
            $key = sprintf('%s.name', $index);

            $response->assertJson(fn(AssertableJson $json) => $json->where($key, $region->name));
        });

        $this->assertCount($regions->count(), $response->decodeResponseJson());
    }

    /** @test */
    public function it_should_return_all_regions_by_region_manager_id_when_auth_user_has_region_manager_role()
    {
        $manager    = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $department = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);

        Region::factory()->times(10)->create([
            'department_id'     => $department->id,
            'region_manager_id' => $this->regionManager->id,
        ]);
        /** @var Collection $regions */
        $regions = Region::factory()->times(2)->create([
            'department_id'     => $department->id,
            'region_manager_id' => $manager->id,
        ]);

        $response = $this->actingAs($manager)
            ->getJson(route('getRegions', ['department' => $department]))
            ->assertSuccessful()
            ->assertOk();

        $regions->each(function (Region $region, $index) use ($response) {
            $key = sprintf('%s.name', $index);

            $response->assertJson(fn(AssertableJson $json) => $json->where($key, $region->name));
        });

        $this->assertCount($regions->count(), $response->decodeResponseJson());
    }

    /** @test */
    public function it_should_return_region_by_region_id_when_auth_user_has_office_manager_role()
    {
        $department = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);
        /** @var Collection $regions */
        $regions = Region::factory()->times(2)->create([
            'department_id'     => $department->id,
            'region_manager_id' => $this->regionManager->id,
        ]);
        $office  = Office::factory()->create([
            'office_manager_id' => $this->officeManager->id,
            'region_id'         => $regions->first()->id,
        ]);

        $this->officeManager->update(['office_id' => $office->id]);

        $response = $this->actingAs($this->officeManager)
            ->getJson(route('getRegions', ['department' => $department]))
            ->assertSuccessful()
            ->assertOk()
            ->decodeResponseJson();

        $this->assertCount(1, $response);
        $this->assertEquals($regions->first()->name, $response[0]['name']);
        $this->assertNotEquals($regions->last()->name, $response[0]['name']);
    }

    /** @test */
    public function it_should_list_all_regions()
    {
        [$departmentManager, $department] = $this->createVP();

        $regionManager = User::factory()->create([
            'role'          => Role::REGION_MANAGER,
            'department_id' => $department->id,
        ]);

        /** @var Collection|Region[] $regions */
        $regions = Region::factory()->count(6)->create(['department_id' => $department->id]);
        $regions->each(fn(Region $region) => $region->managers()->attach($regionManager->id));

        $response = $this->actingAs($departmentManager)
            ->get(route('castle.regions.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.regions.index')
            ->assertViewHas('regions');

        $regions->each(fn(Region $region) => $response->assertSee($region->name));
    }

    /** @test */
    public function it_should_block_access_to_regions()
    {
        $officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $setter        = User::factory()->create(['role' => Role::SETTER]);
        $salesRep      = User::factory()->create(['role' => Role::SALES_REP]);

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
        $owner             = User::factory()->create(['role' => Role::OWNER]);
        $admin             = User::factory()->create(['role' => Role::ADMIN]);
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $regionManager     = User::factory()->create(['role' => Role::REGION_MANAGER]);

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
