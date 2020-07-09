<?php

namespace Tests\Unit\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Unit\UnitTest;

class ChangePasswordTest extends UnitTest
{
    /** @test */
    public function it_should_work()
    {
        Hash::shouldReceive('make')
            ->once()
            ->andReturn('321');

        $user = new User();
        $user->changePassword("123");
        $this->assertEquals("321", $user->password);
    }
}
