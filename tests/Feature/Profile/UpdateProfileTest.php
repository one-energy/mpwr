<?php

namespace Tests\Feature\Profile;

use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class UpdateProfileTest extends FeatureTest
{
    /** @test */
    public function it_should_work()
    {
        $user = (new UserBuilder())
            ->save()
            ->get();

        $this->actingAs($user);

        $this->put(route('profile.update'), [
            'first_name'  => 'Joe',
            'last_name' => 'Doe',
            'email' => 'joe@doe.com',
        ]);

        $user->refresh();

        $this->assertEquals('Joe', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
        $this->assertEquals('joe@doe.com', $user->email);
    }

    //region Validations

    /** @test */
    public function name_should_be_required()
    {
        $user = (new UserBuilder())
            ->save()
            ->get();

        $this->actingAs($user);

        $this->put(route('profile.update'), [])
            ->assertSessionHasErrors([
                'first_name' => __('validation.required', ['attribute' => 'first_name']),
            ]);
    }

    /** @test */
    public function name_should_have_a_min_of_3_characters()
    {
        $user = (new UserBuilder())
            ->save()
            ->get();

        $this->actingAs($user);

        $this->put(route('profile.update'), ['first_name' => '12'])
            ->assertSessionHasErrors([
                'first_name' => __('validation.min.string', ['attribute' => 'first_name', 'min' => 3]),
            ]);
    }

    /** @test */
    public function name_should_have_a_max_of_255_characters()
    {
        $user = (new UserBuilder())
            ->save()
            ->get();

        $this->actingAs($user);

        $this->put(route('profile.update'), ['name' => str_repeat('*', 256)])
            ->assertSessionHasErrors([
                'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255]),
            ]);
    }

    /** @test */
    public function email_should_be_required()
    {
        $user = (new UserBuilder())
            ->save()
            ->get();

        $this->actingAs($user);

        $this->put(route('profile.update'), [])
            ->assertSessionHasErrors([
                'email' => __('validation.required', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_should_be_a_valid_email()
    {
        $user = (new UserBuilder())
            ->save()
            ->get();

        $this->actingAs($user);

        $this->put(route('profile.update'), ['email' => 'invalid-email'])
            ->assertSessionHasErrors([
                'email' => __('validation.email', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_unique_only_for_different_users()
    {
        $user = (new UserBuilder)->withEmail('joe@doe.com')->save()->get();

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'first_name'  => 'Testing',
                'email' => 'joe@doe.com',
            ])
            ->assertSessionHasNoErrors();
    }

    /** @test */
    public function email_should_be_unique()
    {
        (new UserBuilder)->withEmail('joe@doe.com')->save();

        $user = (new UserBuilder)->save()->get();

        $this->actingAs($user)
            ->put(route('profile.update'), ['email' => 'joe@doe.com'])
            ->assertSessionHasErrors([
                'email' => __('validation.unique', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_should_be_a_max_of_128_characters()
    {
        $user = (new UserBuilder())
            ->save()
            ->get();

        $this->actingAs($user);

        $this->put(route('profile.update'), ['email' => 'email' . str_repeat('*', 129) . '@email.com'])
            ->assertSessionHasErrors([
                'email' => __('validation.max.string', ['attribute' => 'email', 'max' => 128]),
            ]);
    }
    //endregion
}
