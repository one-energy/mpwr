<?php

namespace Tests\Feature\NumberTracker;

use App\Enum\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetNumberTrackerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_render_number_tracking_view()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        Department::factory()->create(['name' => 'DevSquad Department']);

        $this->actingAs($john)
            ->get(route('number-tracking.index'))
            ->assertViewIs('number-tracking')
            ->assertSee('DevSquad Department')
            ->assertSuccessful()
            ->assertOk();
    }
}
