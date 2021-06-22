<?php

namespace Tests\Feature\NumberTracker;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\NumberTrackerDetail;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Builders\DailyEntryBuilder;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class NumberTrackerTest extends TestCase
{
    use RefreshDatabase;

    private User $dptManager;

    private User $regionManager;

    private User $officeManager;

    private Department $department;

    private Region $region;

    private Office $office;

    private DailyNumber $officeManagerEntry;

    private DailyNumber $johnEntry;

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

        /** @var Department department */
        $this->department = Department::factory()->create();
        $this->department->managers()->attach($this->dptManager->id);

        $this->region = Region::factory()->create(['department_id' => $this->department->id]);
        $this->region->managers()->attach($this->regionManager->id);

        $this->office = Office::factory()->create(['region_id' => $this->region->id]);
        $this->office->managers()->attach($this->officeManager->id);

        $this->john = User::factory()->create([
            'role'          => Role::SALES_REP,
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
        ]);

        $this->dptManager->update([
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
        ]);
        $this->dptManager->update([
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
        ]);

        $this->regionManager->update([
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
        ]);

        $this->officeManager->update([
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
        ]);

        $this->actingAs($this->dptManager);

        $this->officeManagerEntry = DailyNumber::factory()->create([
            'user_id' => $this->officeManager->id,
            'date'    => now(),
            'doors'   => 15,
        ]);
        $this->johnEntry          = DailyNumber::factory()->create([
            'user_id' => $this->john->id,
            'date'    => now(),
            'doors'   => 15,
        ]);
    }

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
