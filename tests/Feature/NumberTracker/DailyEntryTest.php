<?php

namespace Tests\Feature\NumberTracker;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Tests\Unit\UnitTest;
use App\Http\Livewire\NumberTracker\DailyEntry;
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
        $region        = factory(Region::class)->create(['region_manager_id' => $this->user->id]);
        $officeManager = factory(User::class)->create(['role' => 'Office Manager']);
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

    /** @test */
    public function it_should_show_users_per_date() {
        $today = date("Y-m-d", time());
        $yesterday = date("Y-m-d",  strtotime($today  . '-1 day'));

        $region = (new RegionBuilder)->save()->get();
        //create regions

        $officeOne = factory(Office::class)->create([
            'region_id' => $region->id,
            'office_manager_id' => $region->id
        ]);

        $userOne = (new UserBuilder)->withOffice($officeOne)->save()->get();
        $userTwo = (new UserBuilder)->withOffice($officeOne)->save()->get();

        $dailyEntryOne          = (new DailyEntryBuilder)->withUser($userOne->id)->withDate($today)->save()->get();
        $dailyEntryOneYesterday = (new DailyEntryBuilder)->withUser($userTwo->id)->withDate($yesterday)->save()->get();
        
        $dailyEntryTwo          = (new DailyEntryBuilder)->withUser($userOne->id)->withDate($today)->save()->get();
        $dailyEntryTwoYesterday = (new DailyEntryBuilder)->withUser($userTwo->id)->withDate($yesterday)->save()->get();
        
        Livewire::test(DailyEntry::class)
            ->set('date', $today)
            ->call('setDate')
            ->assertSet('dateSelected', $today);
 

        Livewire::test(DailyEntry::class)
            ->set('date', $yesterday)
            ->call('setDate')
            ->assertSet('dateSelected', $yesterday);
    
    }

    /** @test */
    public function it_should_show_users_per_regions() {

        $region = (new RegionBuilder)->save()->get();
        //create regions
        $officeOne = (new OfficeBuilder)->region($region)->save()->get();
        $officeTwo = (new OfficeBuilder)->region($region)->save()->get();

        //create user to region one
        $userOne    = (new UserBuilder)->withOffice($officeOne)->save()->get();
        $userTwo    = (new UserBuilder)->withOffice($officeOne)->save()->get();
        $userThree  = (new UserBuilder)->withOffice($officeOne)->save()->get();
        $userFour   = (new UserBuilder)->withOffice($officeOne)->save()->get();
        $userFive   = (new UserBuilder)->withOffice($officeOne)->save()->get();
        
        //create user to region two
        $userSix    = (new UserBuilder)->withOffice($officeTwo)->save()->get();
        $userSeven  = (new UserBuilder)->withOffice($officeTwo)->save()->get();
        $userEight  = (new UserBuilder)->withOffice($officeTwo)->save()->get();
        $userNine   = (new UserBuilder)->withOffice($officeTwo)->save()->get();
        $userTen    = (new UserBuilder)->withOffice($officeTwo)->save()->get();

        //first region 
        Livewire::test(DailyEntry::class)
            ->call('setOffice',$officeOne->id)
            ->assertSee($userOne->first_name . " " . $userOne->last_name) 
            ->assertSee($userTwo->first_name . " " . $userTwo->last_name) 
            ->assertSee($userThree->first_name . " " . $userThree->last_name) 
            ->assertSee($userFour->first_name . " " . $userFour->last_name) 
            ->assertSee($userFive->first_nam . " " . $userFive->last_name)
            ->assertDontSee($userSix->first_name . " " . $userSix->last_name) 
            ->assertDontSee($userSeven->first_name . " " . $userSeven->last_name) 
            ->assertDontSee($userEight->first_name . " " . $userEight->last_name) 
            ->assertDontSee($userNine->first_name . " " . $userNine->last_name) 
            ->assertDontSee($userTen->first_name . " " . $userTen->last_name); 

        Livewire::test(DailyEntry::class)
            ->call('setOffice',$officeTwo->id)
            ->assertDontSee($userOne->first_name . " " . $userOne->last_name) 
            ->assertDontSee($userTwo->first_name . " " . $userTwo->last_name) 
            ->assertDontSee($userThree->first_name . " " . $userThree->last_name) 
            ->assertDontSee($userFour->first_name . " " . $userFour->last_name) 
            ->assertDontSee($userFive->first_nam . " " . $userFive->last_name)
            ->assertSee($userSix->first_name . " " . $userSix->last_name) 
            ->assertSee($userSeven->first_name . " " . $userSeven->last_name) 
            ->assertSee($userEight->first_name . " " . $userEight->last_name) 
            ->assertSee($userNine->first_name . " " . $userNine->last_name) 
            ->assertSee($userTen->first_name . " " . $userTen->last_name);
        
    }

    /** @test */
    public function it_should_show_offices() 
    {
        $user = factory(User::class)->create(['role' => 'Region Manager']);

        $region        = factory(Region::class)->create(['region_manager_id' => $user->id]);
        $officeManager = factory(User::class)->create(['role' => 'Office Manager']);
        //creating regions
        $offices = factory(Office::class, 5)->create([
            'region_id' => $region->id,
            'office_manager_id' => $officeManager->id
        ]);

        $response = $this->get('number-tracking/create');
        foreach ($offices as $office) {
            $response->assertSee($office->name);
        }

    }

    /** @test */
    public function it_should_create_a_daily_number()
    {
        $user = (new UserBuilder)->save()->get();
        $dailyEntry = (new DailyEntryBuilder)->withUser($user->id)->save()->get();

        $this->assertDatabaseHas('daily_numbers', [
            'id'         => $dailyEntry->id,
            'date'       => $dailyEntry->date,
            'user_id'    => $user->id,
            'doors'      => $dailyEntry->doors,
            'hours'      => $dailyEntry->hours,
            'sets'       => $dailyEntry->sets,
            'sits'       => $dailyEntry->sits,
            'set_closes' => $dailyEntry->set_closes,
            'closes'     => $dailyEntry->closes,
        ]);
    }

    /** @test */
    public function it_should_update_a_daily_number()
    {
        $user = (new UserBuilder)->save()->get();
        $dailyEntry = (new DailyEntryBuilder)->withUser($user->id)->save()->get();

        $dailyEntryUpdated = $dailyEntry;
        $dailyEntryUpdated->doors = rand(0,100);
        $dailyEntryUpdated->hours = rand(0,100);
        $dailyEntryUpdated->sets = rand(0,100);
        $dailyEntryUpdated->sits = rand(0,100);
        $dailyEntryUpdated->set_closes = rand(0,100);
        $dailyEntryUpdated->closes = rand(0,100);

        $dailyEntryUpdated->save();

        $this->assertDatabaseHas('daily_numbers', [
            'id'       => $dailyEntryUpdated->id,
            'date'       => $dailyEntryUpdated->date,
            'user_id'    => $user->id,
            'doors'      => $dailyEntryUpdated->doors,
            'hours'      => $dailyEntryUpdated->hours,
            'sets'       => $dailyEntryUpdated->sets,
            'sits'       => $dailyEntryUpdated->sits,
            'set_closes' => $dailyEntryUpdated->set_closes,
            'closes'     => $dailyEntryUpdated->closes,
        ]);
    }
   
}
