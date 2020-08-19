<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoreboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['role' => 'Admin']);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_show_the_scoreboard()
    {
        $users = factory(User::class, 5)->create();

        $response = $this->get('scoreboard');

        $response->assertStatus(200)
            ->assertViewIs('scoreboard');

        foreach ($users as $user) {
            $response->assertSee($user->dailyNumbers);
        }
    }
}