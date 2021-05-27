<?php

namespace Tests\Feature\NumberTracker;

use App\Http\Livewire\NumberTracker\NumbersRatios;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class RatiosComponentTest extends TestCase
{
    use DatabaseTransactions;

    public User $dptManager;
    public User $regionManager;
    public User $officeManager;
    public User $jhon;

    public Department $department;

    public Region $region;
    
    public Office $office;
    public Office $officeTwo;

    public DailyNumber $dptManagerEntry;
    public DailyNumber $regionManagerEntry;
    public DailyNumber $officeManagerEntry;
    public DailyNumber $jhonEntry;

    protected function setUp(): void
    {
        parent::setUp();

        parent::setUp();

        $this->dptManager    = User::factory()->create(['role' => 'Department Manager']);
        $this->regionManager = User::factory()->create(['role' => 'Region Manager']);
        $this->officeManager = User::factory()->create(['role' => 'Office Manager']);

        $this->department = Department::factory()->create([
            'department_manager_id' => $this->dptManager->id
        ]);

        $this->region     = Region::factory()->create([
            'department_id'     => $this->department->id,
            'region_manager_id' => $this->regionManager->id
        ]);

        $this->office     = Office::factory()->create([
            'region_id'         => $this->region->id,
            'office_manager_id' => $this->officeManager->id,
        ]);

        $this->officeTwo  = Office::factory()->create([
            'region_id'         => $this->region->id,
            'office_manager_id' => $this->officeManager->id,
        ]);

        $this->jhon       = User::factory()->create([
            'role'          => "Sales Rep",
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id
        ]);

        $this->dptManager->update([
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id
        ]);

        $this->regionManager->update([
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id
        ]);

        $this->officeManager->update([
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id
        ]);

        $this->actingAs($this->dptManager);

        $this->officeManagerEntry = DailyNumber::factory()->create([
            'user_id'   => $this->officeManager->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::now(),
        ]);
        
        $this->dptManagerEntry = DailyNumber::factory()->create([
            'user_id'   => $this->dptManager->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::now(),
        ]);

        $this->regionManagerEntry   = DailyNumber::factory()->create([
            'user_id'   => $this->regionManager->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::now(),
        ]);

        $this->jhonEntry = DailyNumber::factory()->create([
            'user_id'   => $this->jhon->id,
            'office_id' => $this->officeTwo->id,
            'date'      => Carbon::now(),
        ]);
    }

    /** @test */
    public function it_should_show_ratios()
    {
        Livewire::test(NumbersRatios::class)
            ->assertSee("D.P.S")
            ->assertSee("H.P. Set")
            ->assertSee("Sit Ratio")
            ->assertSee("Close Ratio");
    }

    /** @test */
    public function it_should_receive_event_an_set_offices_users_deleted()
    {
        $officeArray = [2,4,6];
        $userArray   = [4,2,3];
        $deleted     = true;

        Livewire::test(NumbersRatios::class)
            ->emit("updateNumbers", $officeArray, $userArray, $deleted)
            ->assertSet('offices', $officeArray)
            ->assertSet('users', $userArray)
            ->assertSet('deleteds', $deleted);
    }

    /** @test */
    public function it_should_sum_dps()
    {
        $officeArray = [$this->office->id,$this->officeTwo->id];
        $userArray   = [$this->dptManager->id, $this->regionManager->id, $this->officeManager->id, $this->jhon->id];

        $sumDoors = $this->dptManagerEntry->doors + $this->regionManagerEntry->doors + $this->officeManagerEntry->doors + $this->jhonEntry->doors;
        $sumSets = $this->dptManagerEntry->sets + $this->regionManagerEntry->sets + $this->officeManagerEntry->sets + $this->jhonEntry->sets;

        Livewire::test(NumbersRatios::class)
            ->emit("updateNumbers", $officeArray, $userArray)
            ->assertSeeInOrder(["D.P.S", round($sumDoors/$sumSets, 2), "H.P. Set"]);
    }

    /** @test */
    public function it_should_sum_hps()
    {
        $officeArray = [$this->office->id,$this->officeTwo->id];
        $userArray   = [$this->dptManager->id, $this->regionManager->id, $this->officeManager->id, $this->jhon->id];

        $sumHours = $this->dptManagerEntry->hours_worked + $this->regionManagerEntry->hours_worked + $this->officeManagerEntry->hours_worked + $this->jhonEntry->hours_worked;
        $sumSets = $this->dptManagerEntry->sets + $this->regionManagerEntry->sets + $this->officeManagerEntry->sets + $this->jhonEntry->sets;

        Livewire::test(NumbersRatios::class)
            ->emit("updateNumbers", $officeArray, $userArray)
            ->assertSeeInOrder(["H.P. Set",round($sumHours/$sumSets, 2), "Sit Ratio"]);
    }

    /** @test */
    public function it_should_sum_sit_ratios()
    {
        $officeArray = [$this->office->id,$this->officeTwo->id];
        $userArray   = [$this->dptManager->id, $this->regionManager->id, $this->officeManager->id, $this->jhon->id];

        $sumSits    = $this->dptManagerEntry->sits + $this->regionManagerEntry->sits + $this->officeManagerEntry->sits + $this->jhonEntry->sits;
        $sumSetSits = $this->dptManagerEntry->set_sits + $this->regionManagerEntry->set_sits + $this->officeManagerEntry->set_sits + $this->jhonEntry->set_sits;
        $sumSets    = $this->dptManagerEntry->sets + $this->regionManagerEntry->sets + $this->officeManagerEntry->sets + $this->jhonEntry->sets;

        Livewire::test(NumbersRatios::class)
            ->emit("updateNumbers", $officeArray, $userArray)
            ->assertSeeInOrder(["Sit Ratio",round(($sumSits + $sumSetSits)/$sumSets, 2),  "Close Ratio"]);
    }

    /** @test */
    public function it_should_sum_close_ratios()
    {
        $officeArray = [$this->office->id,$this->officeTwo->id];
        $userArray   = [$this->dptManager->id, $this->regionManager->id, $this->officeManager->id, $this->jhon->id];

        $sumSits      = $this->dptManagerEntry->sits + $this->regionManagerEntry->sits + $this->officeManagerEntry->sits + $this->jhonEntry->sits;
        $sumSetSits   = $this->dptManagerEntry->set_sits + $this->regionManagerEntry->set_sits + $this->officeManagerEntry->set_sits + $this->jhonEntry->set_sits;
        $sumCloses    = $this->dptManagerEntry->closes + $this->regionManagerEntry->closes + $this->officeManagerEntry->closes + $this->jhonEntry->closes;
        $sumSetCloses = $this->dptManagerEntry->set_closes + $this->regionManagerEntry->set_closes + $this->officeManagerEntry->set_closes + $this->jhonEntry->set_closes;

        Livewire::test(NumbersRatios::class)
            ->emit("updateNumbers", $officeArray, $userArray)
            ->assertSeeInOrder(["Close Ratio", round((($sumCloses + $sumSetCloses)/$sumSits + $sumSetSits), 2)]);
    }

    /** @test */
    public function it_should_sum_when_deleted()
    {
        $this->officeTwo->delete();
        $this->jhon->delete();
        $this->jhonEntry->delete();

        $officeArray = [$this->office->id];
        $userArray   = [$this->dptManager->id, $this->regionManager->id, $this->officeManager->id];

        $dailyEntries = [$this->dptManagerEntry, $this->regionManagerEntry, $this->officeManagerEntry];

        Livewire::test(NumbersRatios::class)
            ->emit("updateNumbers", $officeArray, $userArray, true)
            ->assertSet("numbers", $dailyEntries);
    }

    /** @test */
    public function it_should_only_sum_selected_items()
    {
        $officeArray = [$this->office->id];
        $userArray   = [$this->dptManager->id, $this->regionManager->id, $this->officeManager->id];

        $sumDoors = $this->dptManagerEntry->doors + $this->regionManagerEntry->doors + $this->officeManagerEntry->doors;
        $sumSets = $this->dptManagerEntry->sets + $this->regionManagerEntry->sets + $this->officeManagerEntry->sets;

        Livewire::test(NumbersRatios::class)
            ->emit("updateNumbers", $officeArray, $userArray)
            ->assertSee(round($sumDoors/$sumSets, 2));
    }
}