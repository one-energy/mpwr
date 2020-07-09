<?php

namespace Tests\Feature\Castle;

use App\Models\User;
use App\Notifications\YourAccessToTheCastleWasRevoked;
use Illuminate\Support\Facades\Notification;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class RevokeMasterAccessTest extends FeatureTest
{
    /** @test */
    public function it_should_be_able_to_revoke_an_access_of_a_castle_master()
    {
        Notification::fake();

        $master        = (new UserBuilder)->asMaster()->save()->get();
        $anotherMaster = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->patch(route('castle.masters.revoke', $anotherMaster))
            ->assertRedirect();

        $anotherMaster->refresh();

        $this->assertFalse($anotherMaster->isMaster());
    }

    /** @test */
    public function it_should_notify_the_when_access_is_revoked()
    {
        Notification::fake();

        $master        = (new UserBuilder)->asMaster()->save()->get();
        $anotherMaster = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->patch(route('castle.masters.revoke', $anotherMaster));

        Notification::assertSentTo($anotherMaster, YourAccessToTheCastleWasRevoked::class);
    }

    /** @test */
    public function it_should_not_be_able_to_revoke_your_own_access()
    {
        Notification::fake();

        $master = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->patch(route('castle.masters.revoke', $master))
            ->assertRedirect()
            ->assertSessionHas('error', __('You can\'t revoke your own access.'));

        $this->assertTrue($master->refresh()->isMaster());
    }

    /** @test */
    public function only_master_can_revoke_masters_access()
    {
        Notification::fake();

       $nonMaster = (new UserBuilder)->save()->get();
       $master  = (new UserBuilder)->asMaster()->asMaster()->save()->get();

       $this->actingAs($nonMaster)
           ->patch(route('castle.masters.revoke', $master))
           ->assertForbidden();

       $this->assertTrue($master->refresh()->isMaster());
    }
}
