<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EniumPointsTest extends TestCase
{

    use RefreshDatabase;

    public User $user;

    protected function setUp ():void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'Admin']);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_show_enium_points_card()
    {
        $response = $this->get('/');

        $response->assertSee('ENIUM POINTS');
    }
}
