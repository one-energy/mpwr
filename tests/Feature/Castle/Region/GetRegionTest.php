<?php

namespace Tests\Feature\Castle\Region;

use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetRegionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_regions()
    {
        $departmentManager = User::factory()->create(['role' => 'Department Manager']);
        $department        = Department::factory()->create(['department_manager_id' => $departmentManager->id]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $regionManager     = User::factory()->create([
            'role'          => 'Region Manager',
            'department_id' => $department->id,
        ]);
        $regions           = Region::factory()->count(6)->create([
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
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
    public function it_should_block_access_to_regions()
    {
        $officeManager = User::factory()->create(['role' => 'Office Manager']);
        $setter        = User::factory()->create(['role' => 'Setter']);
        $salesRep      = User::factory()->create(['role' => 'Sales Rep']);

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
        $owner             = User::factory()->create(['role' => 'Owner']);
        $admin             = User::factory()->create(['role' => 'Admin']);
        $departmentManager = User::factory()->create(['role' => 'Department Manager']);
        $regionManager     = User::factory()->create(['role' => 'Region Manager']);

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
