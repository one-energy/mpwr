<?php

namespace Tests\Feature\Castle\Region;

use App\Enum\Role;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateRegionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_edit_an_region()
    {
        [$departmentManager, $department] = $this->createVP();

        $regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var Region $region */
        $region = Region::factory()->create([
            'name'          => 'New Region',
            'department_id' => $department->id,
        ]);
        $region->managers()->attach($regionManager->id);

        $data         = $region->toArray();
        $updateRegion = array_merge($data, ['name' => 'Region Edited']);

        $this->actingAs($departmentManager)
            ->put(route('castle.regions.update', $region->id), $updateRegion)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('regions', [
            'id'   => $region->id,
            'name' => 'Region Edited',
        ]);
    }
}
