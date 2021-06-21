<?php

namespace Tests\Feature\NumberTracker;

use App\Http\Livewire\NumberTracker\DailyEntry;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\Enum\Role;
use Carbon\Carbon;
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

    private DailyNumber $johnEntry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dptManager    = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $this->regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $this->officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

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
}
