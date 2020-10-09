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
use Tests\Builders\UserBuilder;
use Tests\Builders\DailyEntryBuilder;
use Tests\Builders\OfficeBuilder;
use Tests\Builders\RegionBuilder;
use Tests\Feature\FeatureTest;


class DailyEntryTest extends FeatureTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_sum_kpi_users_entries () 
    {
        $departmentManager = factory(User::class)->create([
            'role' => 'Department Manager'
        ]);
        $department    = factory(Department::class)->create([
            'department_manager_id' => $departmentManager->id
        ]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $region        = factory(Region::class)->create([
            'region_manager_id' => $this->user->id,
            'department_id' => $department->id
        ]);
        $officeManager = factory(User::class)->create([
            'role' => 'Office Manager',
            'department_id' => $department->id
        ]);
        $office         = factory(Office::class)->create([
            'region_id' => $region->id,
            'office_manager_id' => $officeManager->id
        ]);

        $userOne    = (new UserBuilder)->withOffice($office)->save()->get();
        $userTwo    = (new UserBuilder)->withOffice($office)->save()->get();
        $userThree  = (new UserBuilder)->withOffice($office)->save()->get();
        $userFour   = (new UserBuilder)->withOffice($office)->save()->get();
        
        $dailyEntryOne   = (new DailyEntryBuilder)->withUser($userOne->id)->withDate(date("Y-m-d", time()))->save()->get();
        $dailyEntryTwo   = (new DailyEntryBuilder)->withUser($userTwo->id)->withDate(date("Y-m-d", time()))->save()->get();
        $dailyEntryThree = (new DailyEntryBuilder)->withUser($userThree->id)->withDate(date("Y-m-d", time()))->save()->get();
        $dailyEntryFour  = (new DailyEntryBuilder)->withUser($userFour->id)->withDate(date("Y-m-d", time()))->save()->get();
        
        $lastDailyEntryOne   = (new DailyEntryBuilder)->withUser($userOne->id)->withDate(date("Y-m-d", strtotime('-1 day')))->save()->get();
        $lastDailyEntryTwo   = (new DailyEntryBuilder)->withUser($userTwo->id)->withDate(date("Y-m-d", strtotime('-1 day')))->save()->get();
        $lastDailyEntryThree = (new DailyEntryBuilder)->withUser($userThree->id)->withDate(date("Y-m-d", strtotime('-1 day')))->save()->get();
        $lastDailyEntryFour  = (new DailyEntryBuilder)->withUser($userFour->id)->withDate(date("Y-m-d", strtotime('-1 day')))->save()->get();

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
