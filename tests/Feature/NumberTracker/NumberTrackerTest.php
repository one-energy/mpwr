<?php

namespace Tests\Feature\NumberTracker;

use App\Http\Livewire\NumberTracker\NumberTrackerDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Builders\DailyEntryBuilder;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class NumberTrackerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_change_pariod()
    {
        $master = UserBuilder::build()->asMaster()->save()->get();
        $users  = User::factory()->count(5)->create();

        $this->actingAs($master);

        DailyEntryBuilder::build()->withUser($users[0]->id)->withDate('2020-08-04')->save();
        DailyEntryBuilder::build()->withUser($users[3]->id)->withDate('2020-08-04')->save();
        DailyEntryBuilder::build()->withUser($users[1]->id)->withDate('2020-08-05')->save();
        DailyEntryBuilder::build()->withUser($users[2]->id)->withDate('2020-08-20')->save();
        DailyEntryBuilder::build()->withUser($users[4]->id)->withDate('2020-07-02')->save();

        Livewire::test(NumberTrackerDetail::class)
            ->call('setPeriod', 'w')
            ->assertSet('period', 'w');

        Livewire::test(NumberTrackerDetail::class)
            ->call('setPeriod', 'd')
            ->assertSet('period', 'd');

        Livewire::test(NumberTrackerDetail::class)
            ->call('setPeriod', 'm')
            ->assertSet('period', 'm');
    }
}
