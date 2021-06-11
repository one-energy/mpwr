<?php

namespace Tests\Feature\Profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_update_profile()
    {
        $user = UserBuilder::build()
            ->save()
            ->get();

        $this->actingAs($user);

        $this->put(route('profile.update'), [
            'first_name' => 'Joe',
            'last_name'  => 'Doe',
            'email'      => 'joe@doe.com',
        ]);

        $user->refresh();

        $this->assertEquals('Joe', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
        $this->assertEquals('joe@doe.com', $user->email);
    }

    /** @test */
    public function first_and_last_name_should_be_required()
    {
        $user = UserBuilder::build()
            ->save()
            ->get();

        $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'first_name' => '',
                'last_name'  => '',
            ])
            ->assertSessionHasErrors([
                'first_name',
                'last_name',
            ]);
    }

    /** @test */
    public function first_and_last_name_should_have_a_min_of_3_characters()
    {
        $user = UserBuilder::build()
            ->save()
            ->get();

        $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'first_name' => '12',
                'last_name'  => '12',
            ])
            ->assertSessionHasErrors([
                'first_name',
                'last_name',
            ]);
    }

    /** @test */
    public function first_and_last_name_should_have_a_max_of_255_characters()
    {
        $user = UserBuilder::build()
            ->save()
            ->get();


        $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'first_name' => Str::random(256),
                'last_name'  => Str::random(256),
            ])
            ->assertSessionHasErrors([
                'first_name',
                'last_name',
            ]);
    }

    /** @test */
    public function it_should_require_email()
    {
        $user = UserBuilder::build()
            ->save()
            ->get();

        $this
            ->actingAs($user)
            ->put(route('profile.update'), [])
            ->assertSessionHasErrors([
                'email' => __('validation.required', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function it_should_require_a_valid_email()
    {
        $user = UserBuilder::build()
            ->save()
            ->get();

        $this
            ->actingAs($user)
            ->put(route('profile.update'), ['email' => 'invalid-email'])
            ->assertSessionHasErrors([
                'email' => __('validation.email', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function it_should_require_a_unique_email()
    {
        $user = UserBuilder::build()->withEmail('joe@doe.com')->save()->get();

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'first_name' => 'Testing',
                'last_name'  => 'Smith',
                'email'      => 'joe@doe.com',
            ])
            ->assertSessionHasNoErrors();
    }

    /** @test */
    public function email_should_be_a_max_of_128_characters()
    {
        $user = UserBuilder::build()
            ->save()
            ->get();

        $this->actingAs($user);

        $this->put(route('profile.update'), ['email' => 'email' . str_repeat('*', 129) . '@email.com'])
            ->assertSessionHasErrors([
                'email' => __('validation.max.string', ['attribute' => 'email', 'max' => 128]),
            ]);
    }
}
