<?php

namespace Tests\Feature\NumberTracker;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Tests\Unit\UnitTest;
use App\Http\Livewire\NumberTracker\DailyEntry;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;


class DailyEntryTest extends FeatureTest
{
    /** @test */
    public function it_should_sum_kpi_users_entries () {
        $user = (new UserBuilder)->save()->get();
        $this->actingAs($user);

        Livewire::test(DailyEntry::class)
            ->set('sumDoors', 123)
            ->assertSee(123);
            
        // $dailyEntries = factory(DailyNumber::class)->create();
        
    }
   
}
