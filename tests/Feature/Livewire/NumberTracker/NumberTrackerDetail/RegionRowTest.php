<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\RegionRow;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegionRowTest extends TestCase
{
    use DatabaseTransactions;

    public User $officeManager;

    public User $regionManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $this->regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);
    }

    /** @test */
    public function it_should_be_possible_collapse_region_row()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $region = Region::factory()->create([
            'region_manager_id' => $this->regionManager,
        ]);

        $this->actingAs($john);

        Livewire::test(RegionRow::class, $this->buildProps($region))
            ->assertSet('itsOpen', false)
            ->call('collapseRegion')
            ->assertSet('itsOpen', true);
    }

    /** @test */
    public function it_should_emit_up_when_call_select_region_method()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $region = Region::factory()->create([
            'region_manager_id' => $this->regionManager,
        ]);

        $this->actingAs($john);

        Livewire::test(RegionRow::class, $this->buildProps($region))
            ->call('selectRegion')
            ->assertEmitted('regionSelected');
    }

    /** @test */
    public function it_should_show_only_offices_managed_by_office_manager()
    {

        $region = Region::factory()->create([
            'region_manager_id' => $this->regionManager,
        ]);

        $officeManaged    = Office::factory()->create([
            'office_manager_id' => $this->officeManager,
            'region_id'         => $region
        ]);

        $officeNotManaged = Office::factory()->create([
            'office_manager_id' => User::factory()->create(['role' => Role::REGION_MANAGER]),
            'region_id' => $region
        ]);

        $this->actingAs($this->officeManager);

        Livewire::test(RegionRow::class, $this->buildProps($region))
            ->call('collapseRegion')
            ->assertSet('offices.0.name', $officeManaged->name)
            ->assertNotSet('offices.1.name', $officeNotManaged->name);
    }

    private function buildProps(mixed $region): array
    {
        return [
            'regionId'      => $region->id,
            'withTrashed'   => false,
            'period'        => 'd',
            'selectedDate'  => today(),
            'selectedUsers' => collect()
        ];
    }
}
