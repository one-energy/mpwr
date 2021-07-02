<?php

namespace Tests\Feature\NumberTracker;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\DailyEntry;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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

        $this->dptManager    = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $this->regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $this->officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

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
            'role'          => Role::SALES_REP,
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
        $this->johnEntry          = DailyNumber::factory()->create([
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

        if (Carbon::now()->day !== 1) {
            $carbonPeriod = CarbonPeriod::create(Carbon::now()->firstOfMonth(), Carbon::yesterday());

            foreach ($carbonPeriod as $date) {
                $period[] = $date->toDateString();
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

    /** @test */
    public function it_should_get_offices_that_not_have_entries()
    {
        $missingOffices = [$this->office];
        $someOffice     = Office::factory()->create([
            'region_id'         => $this->region->id,
            'office_manager_id' => $this->officeManager->id,
        ]);

        $missingOffices[] = $someOffice;

        $livewire = Livewire::test(DailyEntry::class)
            ->set('officeSelected', $this->office->id)
            ->set('dateSelected', Carbon::now());

        foreach ($missingOffices as $index => $office) {
            $livewire->assertSet("missingOffices.{$index}.id", $office->id);
        }

        DailyNumber::factory()->create([
            'user_id'   => $this->john->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::yesterday(),
            'doors'     => 15,
        ]);

        Livewire::test(DailyEntry::class)
            ->set('officeSelected', $this->office->id)
            ->set('dateSelected', Carbon::now())
            ->assertSet('missingOffices.0.id', $someOffice->id);
    }

    /** @test */
    public function it_should_set_date()
    {
        Livewire::test(DailyEntry::class)
            ->assertSet('dateSelected', Carbon::now()->toDateString())
            ->set('date', Carbon::yesterday())
            ->call('setDate')
            ->assertSet('dateSelected', Carbon::yesterday()->toDateString());
    }

    /** @test */
    public function it_should_create_a_daily_number()
    {
        Livewire::test(DailyEntry::class)
            ->assertSet('dateSelected', Carbon::now()->toDateString())
            ->set('date', Carbon::yesterday())
            ->call('setDate')
            ->assertSet('dateSelected', Carbon::yesterday()->toDateString());
    }
}
