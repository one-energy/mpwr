<?php

namespace Tests\Feature\Castle\Region;

use App\Models\Region;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
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
        [$departmentManager, $department] = $this->createVP();

        $regionManager = User::factory()->create([
            'role'          => Role::REGION_MANAGER,
            'department_id' => $department->id,
        ]);

        /** @var Collection|Region[] $regions */
        $regions = Region::factory()->count(6)->create(['department_id' => $department->id]);
        $regions->each(fn (Region $region) => $region->managers()->attach($regionManager->id));

        $response = $this->actingAs($departmentManager)
            ->get(route('castle.regions.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.regions.index')
            ->assertViewHas('regions');

        foreach ($regions as $region) {
            $response->assertSee($region->name);
        }
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
