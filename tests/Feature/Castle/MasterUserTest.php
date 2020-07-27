<?php

namespace Tests\Feature\Castle;

use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class MasterUserTest extends FeatureTest
{
    /** @test */
    public function only_master_users_should_enter_the_castle()
    {
        $nonMaster = (new UserBuilder)->save()->get();

        $this->actingAs($nonMaster)
            ->get(route('castle.users.index'))
            ->assertForbidden();

        $master = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.index'))
            ->assertSuccessful();
    }

   
    /* public function masters_of_the_castle_should_be_redirect_strait_to_the_castle_after_login()
    {
        $master = (new UserBuilder())->asMaster()
            ->withEmail('master-of@the-castle.com')
            ->withPassword('sauron')
            ->save()->get();

        $this->post(route('login'), [
            'email'    => 'master-of@the-castle.com',
            'password' => 'sauron',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($master);
    } */
}
