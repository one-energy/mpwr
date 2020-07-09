<?php

namespace Tests\Feature\Castle;

use App\Models\Invitation;
use App\Notifications\MasterInvitation;
use Illuminate\Support\Facades\Notification;
use Tests\Builders\InvitationBuilder;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class MasterInvitationTest extends FeatureTest
{
    /** @test */
    public function it_should_be_able_to_invite_a_new_master_of_the_castle()
    {
        Notification::fake();

        $master = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->post(route('castle.masters.invite'), [
                'email' => 'joe@doe.com',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('message', "The invitation was sent to <span class='font-bold'>joe@doe.com</span>");

        $this->assertDatabaseHas('invitations', [
            'email'  => 'joe@doe.com',
            'master' => true,
        ]);

        $invitation = Invitation::query()->first();

        Notification::assertSentTo($invitation, MasterInvitation::class);

        $this->assertNotNull($invitation->token);
    }

    //region Form

    /** @test */
    public function it_should_have_a_form_with_an_email()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->get(route('castle.masters.invite'))
            ->assertViewIs('castle.masters.invite')
            ->assertSee('Email');
    }

    //endregion

    //region Validations

    /** @test */
    public function you_cant_send_an_invitation_to_yourself()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->post(route('castle.masters.invite'), ['email' => $master->email])
            ->assertSessionHasErrors([
                'email' => __('validation.castle.masters.email.yourself'),
            ]);
    }

    /** @test */
    public function checking_if_the_email_is_already_a_master()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        (new UserBuilder)->withEmail('joe@doe.com')->asMaster()->save()->get();

        $this->actingAs($master)
            ->post(route('castle.masters.invite'), ['email' => 'joe@doe.com'])
            ->assertSessionHasErrors([
                'email' => __('validation.castle.masters.email.in-use'),
            ]);
    }

    /** @test */
    public function email_should_be_required()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->post(route('castle.masters.invite'), [])
            ->assertSessionHasErrors([
                'email' => __('validation.required', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_should_be_a_valid_email()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->post(route('castle.masters.invite'), ['email' => 'not-valid-email'])
            ->assertSessionHasErrors([
                'email' => __('validation.email', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function it_should_not_have_multiple_invitations_to_the_same_email()
    {
        (new InvitationBuilder)->withEmail('joe@doe.com')->save();
        $master = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->post(route('castle.masters.invite'), ['email' => 'joe@doe.com'])
            ->assertSessionHasErrors([
                'email' => 'There is a pending invitation for this email.',
            ]);


    }
    //endregion
}
