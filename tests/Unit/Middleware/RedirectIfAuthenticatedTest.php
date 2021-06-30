<?php

namespace Tests\Unit\Middleware;

use App\Enum\Role;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class RedirectIfAuthenticatedTest extends TestCase
{
    /** @test */
    public function it_should_redirect_to_castle_if_authenticated_user_has_admin_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($john);

        $response = (new RedirectIfAuthenticated())
            ->handle(new Request(), fn() => redirect()->route('castle.users.index'), 'web');

        $this->assertSame(route('castle.users.index'), $response->getTargetUrl());
    }
}
