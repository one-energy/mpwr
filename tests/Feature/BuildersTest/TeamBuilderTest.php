<?php

namespace Tests\Feature\BuildersTest;

use App\Models\User;
use Tests\Builders\TeamBuilder;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class TeamBuilderTest extends FeatureTest
{
    /** @test */
    public function it_should_create_a_team()
    {
        $team = (new TeamBuilder)->save()->get();

        $this->assertDatabaseHas('teams', [
            'id'   => $team->id,
            'name' => $team->name,
        ]);
    }

    /** @test */
    public function it_should_create_a_team_with_a_given_owner()
    {
        $user = (new UserBuilder)->save()->get();
        $team = (new TeamBuilder)->withOwner($user)->save()->get();

        $this->assertDatabaseHas('teams', [
            'id'       => $team->id,
            'name'     => $team->name,
            'owner_id' => $user->id,
        ]);

        $this->assertDatabaseHas('team_user', [
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role'    => User::OWNER,
        ]);
    }

    /** @test */
    public function it_should_be_able_to_add_more_members_to_the_team()
    {
        $team = (new TeamBuilder)->save()->addMembers(2)->get();

        $this->assertCount(3, $team->users);
        $this->assertCount(2, $team->users()->wherePivot('role', '=', User::MEMBER)->get());
    }
}
