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
    public function first_and_last_name_should_be_required()
    {
        $user = (new UserBuilder())
            ->save()
            ->get();

        $this->actingAs($user);

        $data = [
            'first_name' => '',
            'last_name'  => '',
        ];
        
        $response = $this->put(route('profile.update'), $data);
    
        $response->assertSessionHasErrors(
        [
            'first_name',
            'last_name',
        ]);
    }

    /** @test */
    public function first_and_last_name_should_have_a_min_of_3_characters()
    {
        $user = (new UserBuilder())
            ->save()
            ->get();

        $this->actingAs($user);

        $data = [
            'first_name' => '12',
            'last_name'  => '12',
        ];
        
        $response = $this->put(route('profile.update'), $data);
    
        $response->assertSessionHasErrors(
        [
            'first_name',
            'last_name',
        ]);
    }

    /** @test */
    public function first_and_last_name_should_have_a_max_of_255_characters()
    {
        $user = (new UserBuilder())
            ->save()
            ->get();

        $this->actingAs($user);

<<<<<<< HEAD
        $this->put(route('profile.update'), ['first_name' => str_repeat('*', 256)])
            ->assertSessionHasErrors([
                'first_name' => __('validation.max.string', ['attribute' => 'first_name', 'max' => 255]),
            ]);
=======
        $data = [
            'first_name' => str_repeat('*', 256),
            'last_name'  => str_repeat('*', 256),
        ];
        
        $response = $this->put(route('profile.update'), $data);
    
        $response->assertSessionHasErrors(
        [
            'first_name',
            'last_name',
        ]);
>>>>>>> 32b457fefb37cea66babbf18d1cf735ab5c12491
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
                'first_name' => 'Testing',
                'last_name'  => 'Smith',
                'email'      => 'joe@doe.com',
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
