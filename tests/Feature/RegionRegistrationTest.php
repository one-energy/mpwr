<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Tests\Builders\UserBuilder;

class RegionRegistrationTest extends FeatureTest
{
    //region Happy Path

    /** @test */
    public function it_should_create_a_region_with_an_owner()
    {
        $this->post(route('register'), [
            'region'             => 'Region 1',
            'first_name'         => 'Joe',
            'last_name'          => 'Doe',
            'email'              => 'joe@doe.com',
            'email_confirmation' => 'joe@doe.com',
            'password'           => '12345678',
        ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('home'));

        $this->assertTrue(auth()->check());

        $user = User::query()->first();

        $this->assertTrue(user()->is($user));

        $this->assertDatabaseHas('regions', [
            'name'     => 'Region 1',
            'owner_id' => $user->id,
        ]);

        $this->assertDatabaseHas('users', [
            'first_name' => 'Joe',
            'last_name'  => 'Doe',
            'email'      => 'joe@doe.com',
        ]);

        $this->assertDatabaseHas('region_user', [
            'region_id' => 1,
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
            'region'               => 'Region 1',
            'first_name'         => 'Joe',
            'last_name'          => 'Doe',
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
    public function region_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'region' => __('validation.required', ['attribute' => 'region']),
            ]);
    }

    /** @test */
    public function region_should_have_a_min_of_3_characters()
    {
        $this->post(route('register'), ['region' => '12'])
            ->assertSessionHasErrors([
                'region' => __('validation.min.string', ['attribute' => 'region', 'min' => 3]),
            ]);
    }

    /** @test */
    public function region_should_have_a_max_of_255_characters()
    {
        $this->post(route('register'), ['region' => str_repeat('*', 256)])
            ->assertSessionHasErrors([
                'region' => __('validation.max.string', ['attribute' => 'region', 'max' => 255]),
            ]);
    }

    /** @test */
    public function first_name_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'first_name' => __('validation.required', ['attribute' => 'first_name']),
            ]);
    }

    /** @test */
    public function last_name_should_be_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'last_name' => __('validation.required', ['attribute' => 'last_name']),
            ]);
    }

    /** @test */
    public function first_name_should_have_a_min_of_3_characters()
    {
        $this->post(route('register'), ['first_name' => '12'])
            ->assertSessionHasErrors([
                'first_name' => __('validation.min.string', ['attribute' => 'first_name', 'min' => 3]),
            ]);
    }

    /** @test */
    public function last_name_should_have_a_min_of_3_characters()
    {
        $this->post(route('register'), ['last_name' => '12'])
            ->assertSessionHasErrors([
                'last_name' => __('validation.min.string', ['attribute' => 'last_name', 'min' => 3]),
            ]);
    }

    /** @test */
    public function first_name_should_have_a_max_of_255_characters()
    {
        $this->post(route('register'), ['first_name' => str_repeat('*', 256)])
            ->assertSessionHasErrors([
                'first_name' => __('validation.max.string', ['attribute' => 'first_name', 'max' => 255]),
            ]);
    }

     /** @test */
     public function last_name_should_have_a_max_of_255_characters()
     {
         $this->post(route('register'), ['last_name' => str_repeat('*', 256)])
             ->assertSessionHasErrors([
                 'last_name' => __('validation.max.string', ['attribute' => 'last_name', 'max' => 255]),
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