<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /** @test */
    public function it_should_throws_if_no_role_provided()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You need to inform at least one role!');

        (new Role())->handle(new Request(), fn() => new Response('some content'), '');
    }

    /** @test */
    public function it_should_throws_if_invalid_role_provided()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You need to inform at least one valid role!');

        (new Role())->handle(new Request(), fn() => new Response('some content'), '   ');
    }
}
