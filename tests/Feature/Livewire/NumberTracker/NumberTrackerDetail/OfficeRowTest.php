<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Http\Livewire\NumberTracker\OfficeRow;
use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OfficeRowTest extends TestCase
{
    use RefreshDatabase;

    public User $officeManager;

    public User $regionManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->officeManager = User::factory()->create(['role' => 'Office Manager']);
        $this->regionManager = User::factory()->create(['role' => 'Region Manager']);
    }

    /** @test */
    public function it_should_be_possible_collapse_office_row()
    {
        $region = Region::factory()->create([
            'region_manager_id' => $this->regionManager,
        ]);
        $office = Office::factory()->create([
            'office_manager_id' => $this->officeManager,
            'region_id'         => $region->id,
        ]);

        Livewire::test(OfficeRow::class, ['office' => $office])
            ->assertSet('itsOpen', false)
            ->call('collapseOffice')
            ->assertSet('itsOpen', true);
    }

    /** @test */
    public function it_should_emit_up_when_call_select_office_method()
    {
        $region = Region::factory()->create([
            'region_manager_id' => $this->regionManager,
        ]);
        $office = Office::factory()->create([
            'office_manager_id' => $this->officeManager,
            'region_id'         => $region->id,
        ]);

        Livewire::test(OfficeRow::class, ['office' => $office])
            ->call('selectOffice')
            ->assertEmitted('toggleOffice');
    }

    /** @test */
    public function it_should_mark_as_selected_when_region_selected_is_called()
    {
        $region = Region::factory()->create(['region_manager_id' => $this->regionManager]);
        $office = Office::factory()->create([
            'office_manager_id' => $this->officeManager,
            'region_id'         => $region->id,
        ]);

        Livewire::test(OfficeRow::class, ['office' => $office])
            ->assertSet('selected', false)
            ->call('regionSelected', $region->id, true)
            ->assertSet('selected', true);
    }

    /** @test */
    public function it_should_sum_daily_numbers_by_field()
    {
        $region = Region::factory()->create(['region_manager_id' => $this->regionManager]);
        $office = Office::factory()->create([
            'office_manager_id' => $this->officeManager,
            'region_id'         => $region->id,
        ]);

        $dummy01 = User::factory()->create(['role' => 'Sales Rep', 'office_id' => $office->id]);
        $dummy02 = User::factory()->create(['role' => 'Sales Rep', 'office_id' => $office->id]);

        DailyNumber::factory()->create([
            'doors'        => 10,
            'hours_worked' => 0,
            'office_id'    => $office->id,
            'user_id'      => $dummy01->id,
        ]);

        DailyNumber::factory()->create([
            'doors'        => 10,
            'hours_worked' => 0,
            'office_id'    => $office->id,
            'user_id'      => $dummy02->id,
        ]);

        $component = Livewire::test(OfficeRow::class, ['office' => $office->load('dailyNumbers')]);

        $result = $component->call('sumBy', 'doors');

        $this->assertEquals(20, $result->payload['effects']['returns']['sumBy']);

        $result = $component->call('sumBy', 'hours_worked');

        $this->assertEquals(html_entity_decode('&#8212;'), $result->payload['effects']['returns']['sumBy']);
    }
}
