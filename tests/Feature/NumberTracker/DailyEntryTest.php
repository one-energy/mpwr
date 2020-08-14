<?php

namespace Tests\Feature\NumberTracker;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Tests\Unit\UnitTest;
use App\Http\Livewire\NumberTracker\DailyEntry;
use Tests\Builders\UserBuilder;
use Tests\Builders\RegionBuilder;
use Tests\Builders\DailyEntryBuilder;
use Tests\Feature\FeatureTest;


class DailyEntryTest extends FeatureTest
{
    /** @test */
    public function it_should_sum_kpi_users_entries () 
    {
        $this->assertTrue(True);
        
    }

    /** @test */
    public function it_should_show_users_per_date() {
        $today = date("Y-m-d", time());
        $yesterday = date("Y-m-d",  strtotime($today  . '-1 day'));

        $regionOne = (new RegionBuilder)->save()->get();

        $userOne = (new UserBuilder)->withRegion($regionOne)->save()->get();
        $userTwo = (new UserBuilder)->withRegion($regionOne)->save()->get();

        $dailyEntryOne          = (new DailyEntryBuilder)->withUser($userOne->id)->withDate($today)->save()->get();
        $dailyEntryOneYesterday = (new DailyEntryBuilder)->withUser($userTwo->id)->withDate($yesterday)->save()->get();
        
        $dailyEntryTwo          = (new DailyEntryBuilder)->withUser($userOne->id)->withDate($today)->save()->get();
        $dailyEntryTwoYesterday = (new DailyEntryBuilder)->withUser($userTwo->id)->withDate($yesterday)->save()->get();
        
        $livewireTest = Livewire::test(DailyEntry::class)
            ->set('date', $today)
            ->call('setDate')
            ->assertSet('dateSelected', $today)
            ->assertSee($dailyEntryOne->doors)
            ->assertDontSee($dailyEntryOneYesterday->doors);
    
    }

    /** @test */
    public function it_should_show_users_per_regions() {

        //create regions
        $regionOne = (new RegionBuilder)->save()->get();
        $regionTwo = (new RegionBuilder)->save()->get();

        //create user to region one
        $userOne = (new UserBuilder)->withRegion($regionOne)->save()->get();
        $userTwo = (new UserBuilder)->withRegion($regionOne)->save()->get();
        $userThree = (new UserBuilder)->withRegion($regionOne)->save()->get();
        $userFour = (new UserBuilder)->withRegion($regionOne)->save()->get();
        $userFive = (new UserBuilder)->withRegion($regionOne)->save()->get();
        
        //create user to region two
        $userSix = (new UserBuilder)->withRegion($regionTwo)->save()->get();
        $userSeven = (new UserBuilder)->withRegion($regionTwo)->save()->get();
        $userEight = (new UserBuilder)->withRegion($regionTwo)->save()->get();
        $userNine = (new UserBuilder)->withRegion($regionTwo)->save()->get();
        $userTen = (new UserBuilder)->withRegion($regionTwo)->save()->get();

        //first region 
        Livewire::test(DailyEntry::class)
            ->call('setRegion',$regionOne->id)
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

        //change region
        Livewire::test(DailyEntry::class)
            ->call('setRegion',$regionTwo->id)
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
    public function it_should_show_regions() 
    {
        //creating regions
        $regionOne = (new RegionBuilder)->save()->get();
        $regionTwo = (new RegionBuilder)->save()->get();
        $regionThree = (new RegionBuilder)->save()->get();
        $regionFour = (new RegionBuilder)->save()->get();
        $regionFive = (new RegionBuilder)->save()->get();

        Livewire::test(DailyEntry::class)
            ->assertSee($regionOne->name) 
            ->assertSee($regionTwo->name) 
            ->assertSee($regionThree->name) 
            ->assertSee($regionFour->name) 
            ->assertSee($regionFive->name); 

    }
   
}
