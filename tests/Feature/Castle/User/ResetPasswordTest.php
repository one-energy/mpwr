<?php

namespace Tests\Feature\Castle\User;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_render_reset_password_view()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($john)
            ->get(route('castle.users.request-reset-password', ['user' => $john]))
            ->assertViewIs('castle.users.reset-password');
    }

    /** @test */
    public function it_should_be_possible_reset_password()
    {
        /** @var User $john */
        $john        = User::factory()->create(['role' => Role::ADMIN]);
        $newPassword = Str::random(10);

        $this->actingAs($john)
            ->put(route('castle.users.reset-password', ['user' => $john]), [
                'new_password'              => $newPassword,
                'new_password_confirmation' => $newPassword
            ]);

        $john->refresh();

        $this->assertTrue(Hash::check($newPassword, $john->password));
    }

    /** @test */
    public function it_should_require_new_password()
    {
        /** @var User $john */
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($john)
            ->put(route('castle.users.reset-password', ['user' => $john]), [
                'new_password'              => null,
                'new_password_confirmation' => '123'
            ])
            ->assertSessionHasErrors('new_password');
    }

    /** @test */
    public function it_should_require_password_with_min_8_characters()
    {
        /** @var User $john */
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($john)
            ->put(route('castle.users.reset-password', ['user' => $john]), [
                'new_password'              => '12345',
                'new_password_confirmation' => '12345'
            ])
            ->assertSessionHasErrors('new_password');
    }

    /** @test */
    public function it_should_require_confirmed_password()
    {
        /** @var User $john */
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($john)
            ->put(route('castle.users.reset-password', ['user' => $john]), [
                'new_password'              => '12345678',
                'new_password_confirmation' => '123456789'
            ])
            ->assertSessionHasErrors('new_password');
    }
}
