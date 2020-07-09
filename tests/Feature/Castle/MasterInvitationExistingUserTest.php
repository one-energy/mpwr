<?php

namespace Tests\Feature\Castle;

use App\Models\Invitation;
use App\Notifications\MasterExistingUserInvitation;
use App\Notifications\MasterInvitation;
use Illuminate\Support\Facades\Notification;
use Tests\Builders\InvitationBuilder;
use Tests\Builders\NotificationBuilder;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class MasterInvitationExistingUserTest extends FeatureTest
{
    /** @test */
    public function it_should_be_able_to_invite_an_existing_user_to_have_access_to_the_castle()
    {
        Notification::fake();

        $master = (new UserBuilder)->asMaster()->save()->get();

        $user = (new UserBuilder)->withEmail('joe@doe.com')->save()->get();

        $this->actingAs($master)
            ->post(route('castle.masters.invite'), ['email' => 'joe@doe.com'])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('message', "The invitation was sent to <span class='font-bold'>joe@doe.com</span>");

        $this->assertDatabaseHas('invitations', [
            'email'  => 'joe@doe.com',
            'master' => true,
        ]);

        $invitation = Invitation::query()->first();

        $this->assertEquals($user->id, $invitation->user_id);

        Notification::assertNotSentTo($invitation, MasterInvitation::class);
        Notification::assertSentTo($user, MasterExistingUserInvitation::class);
    }

    /** @test */
    public function it_should_be_able_to_accept_the_invitation()
    {
        $user = (new UserBuilder)->save()->get();

        (new InvitationBuilder)->withEmail('joe@doe.com')->for($user)->isAMaster()->save();

        $notification = (new NotificationBuilder)
            ->notification(new MasterExistingUserInvitation)
            ->for($user)
            ->unread()
            ->with([
                'description' => __('You are being invited to enter the Castle. If you choose to accept, you will have access to the Administration Area that few of us have. Be Wise!'),
                'decision'    => route('castle.masters.invite.response'),
            ])
            ->save();

        $this->actingAs($user)
            ->post(route('castle.masters.invite.response'), ['response' => true, 'notification' => $notification->id])
            ->assertRedirect(route('castle.dashboard'));

        $this->assertTrue($user->refresh()->isMaster());
    }

    /** @test */
    public function it_should_be_able_to_reject_the_invitation()
    {
        $user = (new UserBuilder)->save()->get();

        (new InvitationBuilder)->withEmail('joe@doe.com')->for($user)->isAMaster()->save();

        $notification = (new NotificationBuilder)
            ->notification(new MasterExistingUserInvitation)
            ->for($user)
            ->unread()
            ->with([
                'description' => __('You are being invited to enter the Castle. If you choose to accept, you will have access to the Administration Area that few of us have. Be Wise!'),
                'decision'    => route('castle.masters.invite.response'),
            ])
            ->save();

        $this->actingAs($user)
            ->post(route('castle.masters.invite.response'), ['response' => false, 'notification' => $notification->id])
            ->assertRedirect(route('home'));

        $this->assertFalse($user->refresh()->isMaster());
    }

    /** @test */
    public function notification_should_be_required()
    {
        $user = (new UserBuilder)->save()->get();

        $this->actingAs($user)
            ->post(route('castle.masters.invite.response'), [])
            ->assertSessionHasErrors([
                'notification' => __('validation.required', ['attribute' => 'notification']),
            ]);
    }

    /** @test */
    public function notification_should_be_valid()
    {
        $user = (new UserBuilder)->save()->get();

        $this->actingAs($user)
            ->post(route('castle.masters.invite.response'), ['notification' => '123123'])
            ->assertSessionHasErrors([
                'notification' => __('validation.exists', ['attribute' => 'notification']),
            ]);
    }

    /** @test */
    public function response_should_be_required()
    {
        $user = (new UserBuilder)->save()->get();

        $this->actingAs($user)
            ->post(route('castle.masters.invite.response'), [])
            ->assertSessionHasErrors([
                'response' => __('validation.required', ['attribute' => 'response']),
            ]);
    }

    /** @test */
    public function response_should_be_boolean_value()
    {
        $user = (new UserBuilder)->save()->get();

        $this->actingAs($user)
            ->post(route('castle.masters.invite.response'), ['response' => 'abc'])
            ->assertSessionHasErrors([
                'response' => __('validation.boolean', ['attribute' => 'response']),
            ]);
    }
}
