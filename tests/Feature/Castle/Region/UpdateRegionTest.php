<?php

namespace Tests\Feature\Castle\Region;

use App\Models\Department;
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
        $departmentManager = User::factory()->create(['role' => 'Department Manager']);
        $department        = Department::factory()->create(['department_manager_id' => $departmentManager->id]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $regionManager = User::factory()->create(['role' => 'Region Manager']);
        $region        = Region::factory()->create([
            'name'              => 'New Region',
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
        ]);

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
