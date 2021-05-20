<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Http\Livewire\NumberTracker\NumberTrackerDetail;
use App\Models\DailyNumber;
use App\Models\User;
use Livewire\Livewire;
use Tests\Feature\FeatureTest;

class GetTopTenTrackersTest extends FeatureTest
{
    /** @test */
    public function it_should_be_possible_order_by_doors()
    {
        $john = User::factory()->create(['role' => 'Admin']);

        $setter01 = User::factory()->create(['role' => 'Setter']);
        $setter02 = User::factory()->create(['role' => 'Setter']);

        DailyNumber::factory()->create([
            'user_id' => $setter01->id,
            'date'    => now()->toDateString(),
            'doors'   => 1,
        ]);
        DailyNumber::factory()->create([
            'user_id' => $setter02->id,
            'date'    => now()->toDateString(),
            'doors'   => 2,
        ]);

        $this->actingAs($john);

        Livewire::test(NumberTrackerDetail::class)
            ->set('selectedPill', 'doors')
            ->assertSet('selectedPill', 'doors')
            ->assertCount('topTenTrackers', 2)
            ->assertSeeInOrder([$setter02->full_name, $setter01->full_name]);
    }

    /** @test */
    public function it_should_be_possible_order_by_sg_sits()
    {
        $john = User::factory()->create(['role' => 'Admin']);

        $setter01 = User::factory()->create(['role' => 'Sales Rep']);
        $setter02 = User::factory()->create(['role' => 'Sales Rep']);

        DailyNumber::factory()->create([
            'user_id'  => $setter01->id,
            'date'     => now()->toDateString(),
            'sits'     => 2,
            'set_sits' => 2,
        ]);
        DailyNumber::factory()->create([
            'user_id'  => $setter02->id,
            'date'     => now()->toDateString(),
            'sits'     => 1,
            'set_sits' => 1,
        ]);

        $this->actingAs($john);

        Livewire::test(NumberTrackerDetail::class)
            ->set('selectedPill', 'sg sits')
            ->assertSet('selectedPill', 'sg_sits')
            ->assertCount('topTenTrackers', 2)
            ->assertSeeInOrder([$setter02->full_name, $setter01->full_name]);
    }
}
