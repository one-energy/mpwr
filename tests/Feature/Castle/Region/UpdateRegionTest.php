<?php

namespace Tests\Feature\Castle\Region;

use App\Enum\Role;
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
    public function it_should_render_edit_view()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        [$departmentManager, $department] = $this->createVP();

        $regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var Region $region */
        $region = Region::factory()->create([
            'name'          => 'New Region',
            'department_id' => $department->id,
        ]);
        $region->managers()->attach($regionManager->id);

        $this->actingAs($john)
            ->get(route('castle.regions.edit', $region->id))
            ->assertViewIs('castle.regions.edit')
            ->assertSuccessful()
            ->assertOk();
    }

    /** @test */
    public function it_should_edit_an_region()
    {
        $region            = $this->createRegion();
        $departmentManager = $region->department->departmentAdmin;

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

    private function createRegion(): Region
    {
        /** @var User $departmentManager */
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        /** @var Department $department */
        $department = Department::factory()->create(['department_manager_id' => $departmentManager->id]);

        $departmentManager->update(['department_id' => $department->id]);

        /** @var User $regionManager */
        $regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);

        return Region::factory()->create([
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
        ]);
    }
}
