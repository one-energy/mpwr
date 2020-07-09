<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Tests\Builders\UserBuilder;

class TeamRegistrationTest extends FeatureTest
{
    //region Happy Path

    /** @test */
    public function it_should_create_a_team_with_an_owner()
    {
        $this->post(route('register'), [
            'team'               => 'Team 1',
            'name'               => 'Joe Doe',
            'email'              => 'joe@doe.com',
            'email_confirmation' => 'joe@doe.com',
            'password'           => '12345678',
        ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('home'));

        $this->assertTrue(auth()->check());

        $user = User::query()->first();

        $this->assertTrue(user()->is($user));

        $this->assertDatabaseHas('teams', [
            'name'     => 'Team 1',
            'owner_id' => $user->id,
        ]);

        $this->assertDatabaseHas('users', [
            'name'  => 'Joe Doe',
            'email' => 'joe@doe.com',
        ]);

        $this->assertDatabaseHas('team_user', [
            'team_id' => 1,
            'user_id' => 1,
            'role'    => 'owner',
        ]);
    }
    //endregion

    //region Business Rules
    /** @test */
    public function it_should_send_a_notification_to_the_user_asking_email_confirmation()
    {
        Notification::fake();

        $this->post(route('register'), [
            'team'               => 'Team 1',
            'name'               => 'Joe Doe',
            'email'              => 'joe@doe.com',
            'email_confirmation' => 'joe@doe.com',
            'password'           => '12345678',
        ]);

        Notification::assertSentTo(
            User::query()->first(),
            VerifyEmail::class
        );
    }

    /** @test */
    public function all_auth_routes_should_be_only_be_accessed_after_email_verification()
    {
        $user = (new UserBuilder)->emailUnverified()->save()->get();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertRedirect(route('verification.notice'));

        $user = (new UserBuilder)->emailVerified()->save()->get();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertSuccessful();
    }
    //endregion

    //region Validations
    /** @test */
    public function team_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'team' => __('validation.required', ['attribute' => 'team']),
            ]);
    }

    /** @test */
    public function team_should_have_a_min_of_3_characters()
    {
        $this->post(route('register'), ['team' => '12'])
            ->assertSessionHasErrors([
                'team' => __('validation.min.string', ['attribute' => 'team', 'min' => 3]),
            ]);
    }

    /** @test */
    public function team_should_have_a_max_of_255_characters()
    {
        $this->post(route('register'), ['team' => str_repeat('*', 256)])
            ->assertSessionHasErrors([
                'team' => __('validation.max.string', ['attribute' => 'team', 'max' => 255]),
            ]);
    }

    /** @test */
    public function name_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'name' => __('validation.required', ['attribute' => 'name']),
            ]);
    }

    /** @test */
    public function name_should_have_a_min_of_3_characters()
    {
        $this->post(route('register'), ['name' => '12'])
            ->assertSessionHasErrors([
                'name' => __('validation.min.string', ['attribute' => 'name', 'min' => 3]),
            ]);
    }

    /** @test */
    public function name_should_have_a_max_of_255_characters()
    {
        $this->post(route('register'), ['name' => str_repeat('*', 256)])
            ->assertSessionHasErrors([
                'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255]),
            ]);
    }

    /** @test */
    public function email_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'email' => __('validation.required', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_should_be_a_valid_email()
    {
        $this->post(route('register'), ['email' => 'invalid-email'])
            ->assertSessionHasErrors([
                'email' => __('validation.email', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_should_be_unique()
    {
        factory(User::class)->create(['email' => 'joe@doe.com']);

        $this->post(route('register'), ['email' => 'joe@doe.com'])
            ->assertSessionHasErrors([
                'email' => __('validation.unique', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_should_be_confirmed()
    {
        $this->post(route('register'), ['email' => 'joe@doe.com'])
            ->assertSessionHasErrors([
                'email' => __('validation.confirmed', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_should_be_a_max_of_128_characters()
    {
        $this->post(route('register'), ['email' => str_repeat('*', 129)])
            ->assertSessionHasErrors([
                'email' => __('validation.max.string', ['attribute' => 'email', 'max' => 128]),
            ]);
    }

    /** @test */
    public function password_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'password' => __('validation.required', ['attribute' => 'password']),
            ]);
    }

    /** @test */
    public function password_should_be_a_min_of_8_characters()
    {
        $this->post(route('register'), ['password' => '1234567'])
            ->assertSessionHasErrors([
                'password' => __('validation.min.string', ['attribute' => 'password', 'min' => 8]),
            ]);
    }

    /** @test */
    public function password_should_be_a_max_of_128_characters()
    {
        $this->post(route('register'), ['password' => str_repeat('*', 129)])
            ->assertSessionHasErrors([
                'password' => __('validation.max.string', ['attribute' => 'password', 'max' => 128]),
            ]);
    }
    //endregion
}
