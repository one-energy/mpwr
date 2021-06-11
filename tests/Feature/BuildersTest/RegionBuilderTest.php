<?php

namespace Tests\Feature\BuildersTest;

use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\RegionBuilder;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class RegionBuilderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_region()
    {
        $departmentManager = User::factory()->create(['role' => 'Department Manager']);
        $department        = Department::factory()->create([
            'name'                  => 'New Department',
            'department_manager_id' => $departmentManager->id,
        ]);
        $region            = RegionBuilder::build()->withDepartment($department)->save()->get();

        $this->assertDatabaseHas('regions', [
            'id'   => $region->id,
            'name' => $region->name,
        ]);
    }

    /** @test */
    public function it_should_create_a_region_with_a_given_owner()
    {
        $user   = UserBuilder::build()->save()->get();
        $region = RegionBuilder::build()->withManager($user)->save()->get();

        $this->assertDatabaseHas('regions', [
            'id'                => $region->id,
            'name'              => $region->name,
            'region_manager_id' => $user->id,
        ]);

    }

    /** @test */
    public function it_should_be_able_to_add_more_offices_to_the_region()
    {
        $user              = UserBuilder::build()->save()->get();
        $departmentManager = User::factory()->create(['role' => 'Department Manager']);
        $department        = Department::factory()->create([
            'name'                  => 'New Department',
            'department_manager_id' => $departmentManager->id,
        ]);

        $region = RegionBuilder::build()->withDepartment($department)->save()->get();

        Office::factory()->count(3)->create([
            'region_id'         => $region->id,
            'office_manager_id' => $user->id,
        ]);

        $this->assertCount(3, $region->offices);
    }
}
