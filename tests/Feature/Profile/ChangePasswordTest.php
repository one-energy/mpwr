<?php

namespace Tests\Feature\Profile;

use Illuminate\Support\ViewErrorBag;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    /** @test */
    public function it_should_change_password()
    {
        $user = UserBuilder::build()
            ->withPassword('12345678')
            ->save()
            ->get();

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertSuccessful();

        $this
            ->followingRedirects()
            ->put(route('profile.change-password'), [
                'current_password'          => '12345678',
                'new_password'              => '123456789',
                'new_password_confirmation' => '123456789',
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_fail_password_do_not_match()
    {
        $user = UserBuilder::build()
            ->withPassword('12345678')
            ->save()
            ->get();

        $this->actingAs($user);

        $this
            ->put(route('profile.change-password'), [
                'current_password'          => '123456',
                'new_password'              => '123456789',
                'new_password_confirmation' => '123456789',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['current_password']);

        /** @var ViewErrorBag $errors */
        $errors   = session()->get('errors');
        $messages = $errors->getBag('default')->getMessages();

        $this->assertEquals("The current password does not match.", $messages['current_password'][0]);
    }

    /** @test */
    public function it_should_fail_current_and_new_passwords_are_the_same()
    {
        $user = UserBuilder::build()
            ->withPassword('123456789')
            ->save()
            ->get();

        $this->actingAs($user);

        $this
            ->put(route('profile.change-password'), [
                'current_password'          => '123456789',
                'new_password'              => '123456789',
                'new_password_confirmation' => '123456789',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['new_password']);

        /** @var ViewErrorBag $errors */
        $errors   = session()->get('errors');
        $messages = $errors->getBag('default')->getMessages();

        $this->assertEquals('The new password and current password must be different.', $messages['new_password'][0]);
    }
}
