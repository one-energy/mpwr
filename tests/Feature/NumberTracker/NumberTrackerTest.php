<?php

namespace Tests\Feature\NumberTracker;

use App\Models\User;
use App\Http\Livewire\NumberTracker\NumberTrackerDetail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\Feature\FeatureTest;
use Tests\Builders\DailyEntryBuilder;
use Tests\Builders\UserBuilder;

class NumberTrackerTest extends FeatureTest
{

    use DatabaseTransactions;
    /** @test */
    public function it_should_change_pariod()
    {
        $today = date("Y-m-d", time());

        $master = (new UserBuilder)->asMaster()->save()->get();
        $users = factory(User::class, 5)->create();

        $this->actingAs($master);

        $dailyEntryOne   = (new DailyEntryBuilder)->withUser($users[0]->id)->withDate('2020-08-04')->save()->get();
        $dailyEntryTwo  = (new DailyEntryBuilder)->withUser($users[3]->id)->withDate('2020-08-04')->save()->get();
        $dailyEntryThree   = (new DailyEntryBuilder)->withUser($users[1]->id)->withDate('2020-08-05')->save()->get();
        $dailyEntryFour = (new DailyEntryBuilder)->withUser($users[2]->id)->withDate('2020-08-20')->save()->get();
        $dailyEntryFive  = (new DailyEntryBuilder)->withUser($users[4]->id)->withDate('2020-07-02')->save()->get();

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

    public function it_should_show_icon_when_user_is_deleted()
    {

        $view = $this->component(Icon::class, ['icon' => "user-blocked"]);
        Livewire::test(DailyEntry::class);
        $view->assertSee("user-blocked");
    }

}
