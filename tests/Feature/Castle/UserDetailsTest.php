<?php

namespace Tests\Feature\Castle;

use Tests\Builders\TeamBuilder;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class UserDetailsTest extends FeatureTest
{
    /** @test */
    public function only_master_users_can_see_user_details()
    {
        $nonMaster = (new UserBuilder)->save()->get();

        $this->actingAs($nonMaster)
            ->get(route('castle.users.show', $nonMaster->id))
            ->assertForbidden();

        $master = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.show', $master->id))
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_show_the_details_for_a_user()
    {
        $master    = (new UserBuilder)->asMaster()->save()->get();
        $nonMaster = (new UserBuilder)->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.show', $master->id))
            ->assertViewIs('castle.users.show')
            ->assertSee($master->first_name)
            ->assertSee($master->last_name)
            ->assertSee($master->email);

        $this->actingAs($master)
            ->get(route('castle.users.show', $nonMaster->id))
            ->assertViewIs('castle.users.show')
            ->assertSee($nonMaster->first_name)
            ->assertSee($nonMaster->last_name)
            ->assertSee($nonMaster->email);
    }

    /** @test */
    public function it_should_show_the_teams_a_user_is_on()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $team1  = (new TeamBuilder)->withOwner($master)->save()->get();
        $team2  = (new TeamBuilder)->withOwner($master)->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.show', $master->id))
            ->assertViewIs('castle.users.show')
            ->assertSee($team1->name)
            ->assertSee($team2->name);
    }
}
