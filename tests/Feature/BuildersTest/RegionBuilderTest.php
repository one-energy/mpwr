<?php

namespace Tests\Feature\BuildersTest;

use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\RegionBuilder;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class RegionBuilderTest extends FeatureTest
{

    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_region()
    {
        $departmentManager = factory(User::class)->create(['role' => 'Department Manager']);
        $department        = factory(Department::class)->create([
            'name'              => 'New Department',
            'department_manager_id' => $departmentManager->id,
        ]);
        $region = (new RegionBuilder)->withDepartment($department)->save()->get();

        $this->assertDatabaseHas('regions', [
            'id'   => $region->id,
            'name' => $region->name,
        ]);
    }

    /** @test */
    public function it_should_create_a_region_with_a_given_owner()
    {
        $user = (new UserBuilder)->save()->get();
        $region = (new RegionBuilder)->withManager($user)->save()->get();

        $this->assertDatabaseHas('regions', [
            'id'       => $region->id,
            'name'     => $region->name,
            'region_manager_id' => $user->id,
        ]);

    }

    /** @test */
    public function it_should_be_able_to_add_more_offices_to_the_region()
    {
        $user = (new UserBuilder)->save()->get();
        $departmentManager = factory(User::class)->create(['role' => 'Department Manager']);
        $department = factory(Department::class)->create([
            'name'              => 'New Department',
            'department_manager_id' => $departmentManager->id,
        ]);
        $region = (new RegionBuilder)->withDepartment($department)->save()->get();
        $offices = factory(Office::class, 3)->create([
            'region_id' => $region->id,
            'office_manager_id' => $user->id,
        ]);
        $this->assertCount(3, $region->offices);
    }
}
