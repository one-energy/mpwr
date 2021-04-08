<?php

namespace Tests\Feature\NumberTracker;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Tests\Unit\UnitTest;
use App\Http\Livewire\NumberTracker\DailyEntry;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Builders\UserBuilder;
use Tests\Builders\DailyEntryBuilder;
use Tests\Builders\OfficeBuilder;
use Tests\Builders\RegionBuilder;
use Tests\Feature\FeatureTest;


class DailyEntryTest extends FeatureTest
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->departmentManager = User::factory()->create([
            'role' => 'Department Manager'
        ]);
        $department        = Department::factory()->create([
            'department_manager_id' => $this->departmentManager->id
        ]);
        $this->departmentManager->department_id = $department->id;
        $this->departmentManager->save();

        $region        = Region::factory()->create([
            'region_manager_id' => $this->user->id,
            'department_id' => $department->id
        ]);
        $officeManager = User::factory()->create([
            'role' => 'Office Manager',
            'department_id' => $department->id
        ]);
        $office         = Office::factory()->create([
            'region_id' => $region->id,
            'office_manager_id' => $officeManager->id
        ]);

        $this->userOne    = User::factory()->create([
            "office_id" => $office->id,
            "department_id" => $department->id
        ]);
        $this->userTwo    = User::factory()->create([
            "office_id" => $office->id,
            "department_id" => $department->id
        ]);
        $this->userThree    = User::factory()->create([
            "office_id" => $office->id,
            "department_id" => $department->id
        ]);
        $this->userFour    = User::factory()->create([
            "office_id" => $office->id,
            "department_id" => $department->id
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_sum_kpi_users_entries ()
    {


        $dailyEntryOne   = (new DailyEntryBuilder)->withUser($this->userOne->id)->withDate(date("Y-m-d", time()))->save()->get();
        $dailyEntryTwo   = (new DailyEntryBuilder)->withUser($this->userTwo->id)->withDate(date("Y-m-d", time()))->save()->get();
        $dailyEntryThree = (new DailyEntryBuilder)->withUser($this->userThree->id)->withDate(date("Y-m-d", time()))->save()->get();
        $dailyEntryFour  = (new DailyEntryBuilder)->withUser($this->userFour->id)->withDate(date("Y-m-d", time()))->save()->get();

        $lastDailyEntryOne   = (new DailyEntryBuilder)->withUser($this->userOne->id)->withDate(date("Y-m-d", strtotime('-1 day')))->save()->get();
        $lastDailyEntryTwo   = (new DailyEntryBuilder)->withUser($this->userTwo->id)->withDate(date("Y-m-d", strtotime('-1 day')))->save()->get();
        $lastDailyEntryThree = (new DailyEntryBuilder)->withUser($this->userThree->id)->withDate(date("Y-m-d", strtotime('-1 day')))->save()->get();
        $lastDailyEntryFour  = (new DailyEntryBuilder)->withUser($this->userFour->id)->withDate(date("Y-m-d", strtotime('-1 day')))->save()->get();

        $this->actingAs($this->departmentManager);

        //test if sum its right
        Livewire::test(DailyEntry::class)
            ->assertSee( $dailyEntryOne->doors + $dailyEntryTwo->doors + $dailyEntryThree->doors + $dailyEntryFour->doors)
            ->assertSee( $dailyEntryOne->hours + $dailyEntryTwo->hours + $dailyEntryThree->hours + $dailyEntryFour->hours)
            ->assertSee( $dailyEntryOne->sets + $dailyEntryTwo->sets + $dailyEntryThree->sets + $dailyEntryFour->sets)
            ->assertSee( $dailyEntryOne->sits + $dailyEntryTwo->sits + $dailyEntryThree->sits + $dailyEntryFour->sits)
            ->assertSee( $dailyEntryOne->set_closes + $dailyEntryTwo->set_closes + $dailyEntryThree->set_closes + $dailyEntryFour->set_closes)
            ->assertSee( $dailyEntryOne->closes + $dailyEntryTwo->closes + $dailyEntryThree->closes + $dailyEntryFour->closes);

        //test if sum its right
        Livewire::test(DailyEntry::class)
            ->assertSee( ($dailyEntryOne->doors + $dailyEntryTwo->doors + $dailyEntryThree->doors + $dailyEntryFour->doors)                     - ($lastDailyEntryOne->doors + $lastDailyEntryTwo->doors + $lastDailyEntryThree->doors + $lastDailyEntryFour->doors))
            ->assertSee( ($dailyEntryOne->hours + $dailyEntryTwo->hours + $dailyEntryThree->hours + $dailyEntryFour->hours)                     - ($lastDailyEntryOne->hours + $lastDailyEntryTwo->hours + $lastDailyEntryThree->hours + $lastDailyEntryFour->hours))
            ->assertSee( ($dailyEntryOne->sets + $dailyEntryTwo->sets + $dailyEntryThree->sets + $dailyEntryFour->sets)                         - ($lastDailyEntryOne->sets + $lastDailyEntryTwo->sets + $lastDailyEntryThree->sets + $lastDailyEntryFour->sets))
            ->assertSee( ($dailyEntryOne->sits + $dailyEntryTwo->sits + $dailyEntryThree->sits + $dailyEntryFour->sits)                         - ($lastDailyEntryOne->sits + $lastDailyEntryTwo->sits + $lastDailyEntryThree->sits + $lastDailyEntryFour->sits))
            ->assertSee( ($dailyEntryOne->set_closes + $dailyEntryTwo->set_closes + $dailyEntryThree->set_closes + $dailyEntryFour->set_closes) - ($lastDailyEntryOne->set_closes + $lastDailyEntryTwo->set_closes + $lastDailyEntryThree->set_closes + $lastDailyEntryFour->set_closes))
            ->assertSee( ($dailyEntryOne->closes + $dailyEntryTwo->closes + $dailyEntryThree->closes + $dailyEntryFour->closes)                 - ($lastDailyEntryOne->closes + $lastDailyEntryTwo->closes + $lastDailyEntryThree->closes + $lastDailyEntryFour->closes));
    }


}
