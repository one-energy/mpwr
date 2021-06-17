<?php

namespace Tests\Feature\NumberTracker;

use App\Http\Livewire\NumberTracker\NumberTrackerDetail;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\View\Components\Icon;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Builders\DailyEntryBuilder;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class NumberTrackerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dptManager = User::factory()->create([
            'department_id' => null,
            'office_id'     => null,
        ]);

        $this->regionManager = User::factory()->create([
            'department_id' => null,
            'office_id'     => null,
        ]);
        $this->officeManager = User::factory()->create([
            'department_id' => null,
            'office_id'     => null,
        ]);

        $this->department = Department::factory()->create([
            'department_manager_id' => $this->dptManager->id,
        ]);

        $this->region = Region::factory()->create([
            'department_id'     => $this->department->id,
            'region_manager_id' => $this->regionManager->id,
        ]);

        $this->office = Office::factory()->create([
            'region_id'         => $this->region->id,
            'office_manager_id' => $this->officeManager->id,
        ]);

        $this->john = User::factory()->create([
            'role'          => 'Sales Rep',
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
        ]);

        $this->dptManager->department_id = $this->department->id;
        $this->dptManager->office_id     = $this->office->id;
        $this->dptManager->save();
        $this->regionManager->department_id = $this->department->id;
        $this->regionManager->office_id     = $this->office->id;
        $this->regionManager->save();
        $this->officeManager->department_id = $this->department->id;
        $this->officeManager->office_id     = $this->office->id;
        $this->officeManager->save();

        $this->actingAs($this->dptManager);

        $this->officeManagerEntry = DailyNumber::factory()->create([
            'user_id' => $this->officeManager->id,
            'date'    => Carbon::now(),
            'doors'   => 15,
        ]);
        $this->johnEntry          = DailyNumber::factory()->create([
            'user_id' => $this->john->id,
            'date'    => Carbon::now(),
            'doors'   => 15,
        ]);
    }

    /** @test */
    public function it_should_change_pariod()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $users  = User::factory()->count(5)->create();

        $this->actingAs($master);

        (new DailyEntryBuilder)->withUser($users[0]->id)->withDate('2020-08-04')->save()->get();
        (new DailyEntryBuilder)->withUser($users[3]->id)->withDate('2020-08-04')->save()->get();
        (new DailyEntryBuilder)->withUser($users[1]->id)->withDate('2020-08-05')->save()->get();
        (new DailyEntryBuilder)->withUser($users[2]->id)->withDate('2020-08-20')->save()->get();
        (new DailyEntryBuilder)->withUser($users[4]->id)->withDate('2020-07-02')->save()->get();

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

    //this test needs to be replaced on inside a user row, dont works here.

    /** @test */
    public function it_should_show_icon_when_user_is_deleted()
    {
        $this->markTestSkipped('must be revisited.');

        $user = User::factory()->create([
            'role'          => 'Sales Rep',
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
            'deleted_at'    => Carbon::now(),
        ]);

        DailyNumber::factory()->create([
            'user_id' => $user->id,
            'office_id' => $this->office->id,
            'date'    => Carbon::yesterday(),
            'doors'   => 15,
        ]);

        Livewire::test(NumberTrackerDetail::class)
            ->set('dateSelected', Carbon::yesterday())
            ->assertSeeHtml('<path d="M354.315,318.023v-23.922c0-41.355-33.645-75-75-75H162.326c-41.355,0-75,33.645-75,75v146.99c0,24.813,20.187,45,45,45    h126.48C276.434,502.176,299.868,512,325.556,512c54.654,0,99.118-44.464,99.118-99.119    C424.674,368.226,394.988,330.379,354.315,318.023z M236.363,456.09H132.326c-8.271,0.001-15-6.728-15-14.999v-146.99    c0-24.813,20.187-45,45-45h116.989c24.813,0,45,20.187,45,45v19.678c-54.084,0.668-97.878,44.863-97.878,99.102    C226.437,428.362,230.007,443.024,236.363,456.09z M256.438,412.882c0-38.112,31.006-69.118,69.118-69.118    c13.637,0,26.353,3.986,37.076,10.831l-95.364,95.362C260.424,439.234,256.438,426.519,256.438,412.882z M325.556,482    c-13.637,0-26.353-3.986-37.075-10.83l95.363-95.363c6.844,10.722,10.83,23.438,10.83,37.074    C394.674,450.994,363.668,482,325.556,482z" fill="#46a049" data-original="#000000" style="" class=""/>');
    }
}
