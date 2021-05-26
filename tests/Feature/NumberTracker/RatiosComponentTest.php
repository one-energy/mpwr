<?php

namespace Tests\Feature\NumberTracker;

use App\Http\Livewire\NumberTracker\NumbersRatios;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RatiosComponentTest extends TestCase
{
    use DatabaseTransactions;

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
}