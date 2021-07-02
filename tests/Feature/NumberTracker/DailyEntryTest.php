<?php

namespace Tests\Feature\NumberTracker;

use App\Http\Livewire\NumberTracker\DailyEntry;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DailyEntryTest extends TestCase
{
    use RefreshDatabase;

    private User $dptManager;

    private User $regionManager;

    private User $officeManager;

    private User $john;

    private Department $department;

    private Region $region;

    private Office $office;

    private DailyNumber $officeManagerEntry;

    private DailyNumber $johnManagerEntry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dptManager    = User::factory()->create(['role' => 'Department Manager']);
        $this->regionManager = User::factory()->create(['role' => 'Region Manager']);
        $this->officeManager = User::factory()->create(['role' => 'Office Manager']);

        $this->department = Department::factory()->create([
            'department_manager_id' => $this->dptManager->id,
        ]);

        $this->region     = Region::factory()->create([
            'department_id'     => $this->department->id,
            'region_manager_id' => $this->regionManager->id,
        ]);

        $this->office     = Office::factory()->create([
            'region_id'         => $this->region->id,
            'office_manager_id' => $this->officeManager->id,
        ]);

        $this->john       = User::factory()->create([
            'role'          => 'Sales Rep',
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
            'user_id'   => $this->officeManager->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::now(),
            'doors'     => 15,
        ]);
        $this->johnEntry = DailyNumber::factory()->create([
            'user_id'   => $this->john->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::now(),
            'doors'     => 15,
        ]);
    }

    /** @test */
    public function it_should_show_user_daily_entry()
    {
        Livewire::test(DailyEntry::class)
            ->set('officeSelected', $this->office->id)
            ->assertSee($this->john->first_name)
            ->assertSee(15);
    }

    /** @test */
    public function it_should_show_sum_of_daily_entry()
    {
        Livewire::test(DailyEntry::class)
             ->set('officeSelected', $this->office->id)
             ->set('dateSelected', Carbon::now())
             ->assertSee($this->officeManager->first_name)
             ->assertSee($this->john->first_name)
             ->assertSee($this->johnEntry->doors + $this->officeManagerEntry->doors);
    }

    /** @test */
    public function it_should_show_deleted_user_that_has_a_daily_entry()
    {

        $user = User::factory()->create([
            'role'          => 'Sales Rep',
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
            'deleted_at'    => Carbon::now(),
        ]);

        DailyNumber::factory()->create([
            'user_id'   => $user->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::yesterday(),
            'doors'     => 15,
        ]);
        Livewire::test(DailyEntry::class)
            ->set('officeSelected', $this->office->id)
            ->set('dateSelected', Carbon::now())
            ->assertDontSee($user->first_name)
            ->set('dateSelected', Carbon::yesterday())
            ->assertSee($user->first_name);
    }

    /** @test */
    public function it_should_get_dates_that_not_have_entries()
    {
        $period = [];

        if (Carbon::now()->day != 1) {
            $carbonPeriod = CarbonPeriod::create(Carbon::now()->firstOfMonth(), Carbon::yesterday());

            foreach ($carbonPeriod as $date) {
                array_push($period, $date->toDateString());
            }
        }


        Livewire::test(DailyEntry::class)
            ->set('officeSelected', $this->office->id)
            ->set('dateSelected', Carbon::now())
            ->assertSet('missingDates', $period);
        
        DailyNumber::factory()->create([
            'user_id'   => $this->john->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::yesterday(),
            'doors'     => 15,
        ]);

        array_pop($period);

        Livewire::test(DailyEntry::class)
            ->set('officeSelected', $this->office->id)
            ->set('dateSelected', Carbon::now())
            ->assertSet('missingDates', $period);
    }
}
