<?php

namespace Tests\Feature\BuildersTest;

use App\Models\Office;
use App\Models\User;
use App\Enum\Role;
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
        [$departmentManager, $department] = $this->createVP();

        $region = RegionBuilder::build()->withDepartment($department)->save()->get();

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
            'id'   => $region->id,
            'name' => $region->name,
        ]);

        $this->assertDatabaseHas('user_managed_regions', [
            'user_id'   => $user->id,
            'region_id' => $region->id
        ]);
    }

    /** @test */
    public function it_should_be_able_to_add_more_offices_to_the_region()
    {
        $officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        [$departmentManager, $department] = $this->createVP();


        $region = RegionBuilder::build()->withDepartment($department)->save()->get();

        Office::factory()
            ->count(3)
            ->create(['region_id' => $region->id])
            ->each(fn(Office $office) => $office->managers()->attach($officeManager->id));

        $this->assertCount(3, $region->offices);
    }
}
