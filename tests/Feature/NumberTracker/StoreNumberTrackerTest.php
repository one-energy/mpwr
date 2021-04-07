<?php

namespace Tests\Feature\NumberTracker;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreNumberTrackerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_require_officeSelected()
    {
        $john = User::factory()->create(['role' => 'Admin']);

        $data =[
            'date'    => now(),
            'numbers' => [],
        ];

        $this
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors('officeSelected');
    }

    /** @test */
    public function it_should_require_numbers()
    {
        $john = User::factory()->create(['role' => 'Admin']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
        ];

        $this
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors('numbers');
    }
}
