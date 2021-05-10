<?php

namespace Tests\Feature\Livewire\NumberTracker\Spreadsheet;

use App\Http\Livewire\NumberTracker\Spreadsheet;
use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SaveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_not_store_new_daily_numbers_if_newDailyNumbers_property_is_empty()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Office Manager']);

        $region = Region::factory()->create(['region_manager_id' => $mary->id]);
        Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $ann->id
        ]);

        $this->actingAs($john);

        $this->assertDatabaseCount('daily_numbers', 0);

        Livewire::test(Spreadsheet::class)
            ->assertSet('newDailyNumbers', [])
            ->call('save')
            ->assertSet('newDailyNumbers', [])
            ->assertDispatchedBrowserEvent('show-alert');

        $this->assertDatabaseCount('daily_numbers', 0);
    }

    /** @test */
    public function it_should_store_new_daily_numbers_if_newDailyNumbers_property_is_not_empty()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Office Manager']);

        $region = Region::factory()->create(['region_manager_id' => $mary->id]);
        $office = Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $ann->id
        ]);

        $setter = User::factory()->create([
            'role'      => 'Setter',
            'office_id' => $office->id
        ]);

        $this->actingAs($john);

        $currentDate = now();

        $this->assertDatabaseCount('daily_numbers', 0);

        Livewire::test(Spreadsheet::class)
            ->assertSet('newDailyNumbers', [])
            ->call('attachNewDailyEntry', ...array_values([
                'index' => 2,
                'user'  => $setter,
                'date'  => $currentDate->format('F dS'),
                'field' => 'sets',
                'value' => 10
            ]))
            ->call('attachNewDailyEntry', ...array_values([
                'index' => 4,
                'user'  => $setter,
                'date'  => $currentDate->format('F dS'),
                'field' => 'doors',
                'value' => 10
            ]))
            ->assertSet('newDailyNumbers.2', [
                'user_id'       => $setter->id,
                'office_id'     => $setter->office_id,
                'date'          => $currentDate->format('Y-m-d'),
                'doors'         => 0,
                'sets'          => 10,
                'set_closes'    => 0,
                'closes'        => 0,
                'hours_worked'  => 0,
                'hours_knocked' => 0,
                'sats'          => 0,
                'closer_sits'   => 0,
            ])
            ->assertSet('newDailyNumbers.4', [
                'user_id'       => $setter->id,
                'office_id'     => $setter->office_id,
                'date'          => $currentDate->format('Y-m-d'),
                'doors'         => 10,
                'sets'          => 0,
                'set_closes'    => 0,
                'closes'        => 0,
                'hours_worked'  => 0,
                'hours_knocked' => 0,
                'sats'          => 0,
                'closer_sits'   => 0,
            ])
            ->call('save')
            ->assertSet('newDailyNumbers', [])
            ->assertDispatchedBrowserEvent('show-alert')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('daily_numbers', 2);
    }

    /** @test */
    public function it_should_update_daily_numbers_if_dailyNumbers_property_is_not_empty()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Office Manager']);

        $region = Region::factory()->create(['region_manager_id' => $mary->id]);
        $office = Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $ann->id
        ]);

        /** @var User $setter */
        $setter = User::factory()->create([
            'role'      => 'Setter',
            'office_id' => $office->id
        ]);

        $dailyNumber = DailyNumber::factory()->create([
            'user_id'   => $setter->id,
            'doors'     => 99,
            'office_id' => $setter->office_id,
            'date'      => now()->format('Y-m-d')
        ]);

        $this->actingAs($john);

        $this->assertDatabaseCount('daily_numbers', 1);
        $this->assertSame(99, $dailyNumber->doors);

        Livewire::test(Spreadsheet::class)
            ->call('updateDailyNumber', ...array_values([
                'dailyNumber' => $dailyNumber,
                'field'       => 'doors',
                'newValue'    => 10
            ]))
            ->call('save')
            ->assertDispatchedBrowserEvent('show-alert')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('daily_numbers', 1);
        $this->assertSame(10, $dailyNumber->fresh()->doors);
    }
}
