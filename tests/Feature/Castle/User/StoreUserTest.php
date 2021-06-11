<?php

namespace Tests\Feature\Castle\User;

use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_require_first_name()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $data = array_merge(User::factory()->raw(), ['first_name' => null]);

        $this->actingAs($john)
            ->post(route('castle.users.store'), $data)
            ->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function it_should_require_last_name()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $data = array_merge(User::factory()->raw(), ['last_name' => null]);

        $this->actingAs($john)
            ->post(route('castle.users.store'), $data)
            ->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function it_should_require_an_email()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $data = array_merge(User::factory()->raw(), ['email' => null]);

        $this->actingAs($john)
            ->post(route('castle.users.store'), $data)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_should_require_a_valid_email()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $data = array_merge(User::factory()->raw(), ['email' => 'sample@.google.com']);

        $this->actingAs($john)
            ->post(route('castle.users.store'), $data)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_should_prevent_first_name_greater_than_255()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $data = array_merge(User::factory()->raw(), ['first_name' => Str::random(256)]);

        $this->actingAs($john)
            ->post(route('castle.users.store'), $data)
            ->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function it_should_prevent_last_name_greater_than_255()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $data = array_merge(User::factory()->raw(), ['last_name' => Str::random(256)]);

        $this->actingAs($john)
            ->post(route('castle.users.store'), $data)
            ->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function it_should_prevent_an_invalid_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $data = array_merge(User::factory()->raw(), ['role' => Str::random(10)]);

        $this->actingAs($john)
            ->post(route('castle.users.store'), $data)
            ->assertSessionHasErrors('role');
    }

    /** @test */
    public function it_should_prevent_an_invalid_office_id()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $data = array_merge(User::factory()->raw(), ['office_id' => 999]);

        $this->actingAs($john)
            ->post(route('castle.users.store'), $data)
            ->assertSessionHasErrors('office_id');
    }

    /** @test */
    public function it_should_prevent_an_invalid_department_id()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $data = array_merge(User::factory()->raw(), ['department_id' => 999]);

        $this->actingAs($john)
            ->post(route('castle.users.store'), $data)
            ->assertSessionHasErrors('department_id');
    }
}
