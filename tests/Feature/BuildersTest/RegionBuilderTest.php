<?php

namespace Tests\Feature\BuildersTest;

use App\Models\User;
use Tests\Builders\RegionBuilder;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class RegionBuilderTest extends FeatureTest
{
    /** @test */
    public function it_should_create_a_region()
    {
        $region = (new RegionBuilder)->save()->get();

        $this->assertDatabaseHas('regions', [
            'id'   => $region->id,
            'name' => $region->name,
        ]);
    }

    /** @test */
    public function it_should_create_a_region_with_a_given_owner()
    {
        $user = (new UserBuilder)->save()->get();
        $region = (new RegionBuilder)->withOwner($user)->save()->get();

        $this->assertDatabaseHas('regions', [
            'id'       => $region->id,
            'name'     => $region->name,
            'owner_id' => $user->id,
        ]);

        $this->assertDatabaseHas('region_user', [
            'region_id' => $region->id,
            'user_id' => $user->id,
            'role'    => 'Owner',
        ]);
    }

    /** @test */
    public function it_should_be_able_to_add_more_members_to_the_region()
    {
        $region = (new RegionBuilder)->save()->addMembers(2)->get();

        $this->assertCount(3, $region->users);
        $this->assertCount(2, $region->users()->wherePivot('role', '=', 'Setter')->get());
    }
}
