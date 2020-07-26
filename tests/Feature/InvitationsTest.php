<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Builders\InvitationBuilder;

class InvitationsTest extends FeatureTest
{
    use WithFaker;

    //region Form View

    /** @test */
    public function it_should_open_a_form_to_accept_the_invitation()
    {
        $invitation = (new InvitationBuilder)->save()->get();

        $this->get(route('register.with-invitation', $invitation))
            ->assertViewIs('auth.register-with-invitation')
            ->assertViewHas('invitation', $invitation);
    }

    /** @test */
    public function it_should_return_an_email_hint()
    {
        $invitation = (new InvitationBuilder)->withEmail('joe-smith@email.com')->save()->get();

        $this->get(route('register.with-invitation', $invitation))
            ->assertViewIs('auth.register-with-invitation')
            ->assertViewHas('email', 'jo*****th@em**l.com');
    }

    //endregion

    /** @test */
    public function registering_with_an_invitation()
    {
        $invitation = (new InvitationBuilder)->isAMaster()->withEmail('joe-smith@email.com')->save()->get();

        $this->post(route('register.with-invitation', $invitation), [
            'first_name'         => 'Joe',
            'last_name'          => 'Doe',
            'email_confirmation' => 'joe-smith@email.com',
            'password'           => '14253647',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'first_name' => 'Joe',
            'last_name'  => 'Doe',
            'email'      => 'joe-smith@email.com',
        ]);

        /** @var User $user */
        $user = User::query()->latest()->first();

        $this->assertTrue($user->isMaster());
        $this->assertNotNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
    }

    //region Validations

    /** @test */
    public function first_and_last__name_are_required()
    {
        $invitation = (new InvitationBuilder)->isAMaster()->withEmail('joe-smith@email.com')->save()->get();

        $this->post(route('register.with-invitation', $invitation), [])
            ->assertSessionHasErrors([
                'first_name' => __('validation.required', ['attribute' => 'first_name']),
                'last_name'  => __('validation.required', ['attribute' => 'last_name']),
            ]);
    }

    /** @test */
    public function first_name_should_have_a_min_of_3_characters()
    {
        $invitation = (new InvitationBuilder)->isAMaster()->withEmail('joe-smith@email.com')->save()->get();

        $this->post(route('register.with-invitation', $invitation), [
            'first_name' => '12',
        ])->assertSessionHasErrors([
            'first_name' => __('validation.min.string', ['attribute' => 'first_name', 'min' => 3]),
        ]);
    }

    /** @test */
    public function first_name_should_have_a_max_of_255_characters()
    {
        $invitation = (new InvitationBuilder)->isAMaster()->withEmail('joe-smith@email.com')->save()->get();

        $this->post(route('register.with-invitation', $invitation), [
            'first_name' => str_repeat('*', 256),
        ])->assertSessionHasErrors([
            'first_name' => __('validation.max.string', ['attribute' => 'first_name', 'max' => 255]),
        ]);
    }

    /** @test */
    public function email_should_be_confirmed()
    {
        $invitation = (new InvitationBuilder)->isAMaster()->withEmail('joe-smith@email.com')->save()->get();

        $this->post(route('register.with-invitation', $invitation), [
            'email_confirmation' => 'nothing@nothing.com',
        ])
            ->assertSessionHasErrors([
                'email' => __('validation.confirmed', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function password_is_required()
    {
        $invitation = (new InvitationBuilder)->isAMaster()->withEmail('joe-smith@email.com')->save()->get();

        $this->post(route('register.with-invitation', $invitation), [])
            ->assertSessionHasErrors([
                'password' => __('validation.required', ['attribute' => 'password']),
            ]);
    }

    /** @test */
    public function password_should_have_a_min_of_8_characters()
    {
        $invitation = (new InvitationBuilder)->isAMaster()->withEmail('joe-smith@email.com')->save()->get();

        $this->post(route('register.with-invitation', $invitation), [
            'password' => '1234567',
        ])->assertSessionHasErrors([
            'password' => __('validation.min.string', ['attribute' => 'password', 'min' => 8]),
        ]);
    }

    /** @test */
    public function password_should_have_a_max_of_255_characters()
    {
        $invitation = (new InvitationBuilder)->isAMaster()->withEmail('joe-smith@email.com')->save()->get();

        $this->post(route('register.with-invitation', $invitation), [
            'password' => str_repeat('*', 129),
        ])->assertSessionHasErrors([
            'password' => __('validation.max.string', ['attribute' => 'password', 'max' => 128]),
        ]);
    }
    //endregion
}
