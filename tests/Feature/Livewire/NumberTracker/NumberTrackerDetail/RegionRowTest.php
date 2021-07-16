<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\RegionRow;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegionRowTest extends TestCase
{
    use RefreshDatabase;

    public User $regionManager;

    public User $officeManager;

    public User $salesRep;

    public User $setter;

    public function setUp(): void
    {
        parent::setUp();

        $this->officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $this->regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $this->salesRep      = User::factory()->create(['role' => Role::SALES_REP]);
        $this->setter        = User::factory()->create(['role' => Role::SETTER]);
    }

    /** @test */
    public function it_should_be_possible_collapse_region_row()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($this->regionManager->id);

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

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($this->regionManager->id);

        $this->actingAs($john);

        Livewire::test(RegionRow::class, $this->buildProps($region))
            ->call('selectRegion')
            ->assertEmitted('regionSelected');
    }

    /** @test */
    public function it_should_show_only_offices_managed_by_office_manager()
    {
        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($this->regionManager->id);

        /** @var Office $officeManaged */
        $officeManaged = Office::factory()->create(['region_id' => $region]);
        $officeManaged->managers()->attach($this->officeManager->id);

        /** @var Office $officeNotManaged */
        $officeNotManaged = Office::factory()->create(['region_id' => $region]);
        $officeNotManaged->managers()->attach(User::factory()->create(['role' => Role::OFFICE_MANAGER]));

        $this->actingAs($this->officeManager);

        Livewire::test(RegionRow::class, $this->buildProps($region))
            ->call('collapseRegion')
            ->assertSet('offices.0.name', $officeManaged->name)
            ->assertNotSet('offices.1.name', $officeNotManaged->name);
    }

    /** @test */
    public function it_should_show_only_offices_of_setters_or_sales_rep()
    {
        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($this->regionManager->id);

        /** @var Office $officeManaged */
        $officeManaged = Office::factory()->create(['region_id' => $region]);
        $officeManaged->managers()->attach($this->officeManager->id);

        /** @var Office $officeNotManaged */
        $officeNotManaged = Office::factory()->create(['region_id' => $region]);
        $officeNotManaged->managers()->attach(User::factory()->create(['role' => Role::OFFICE_MANAGER]));

        $this->setter->update(['office_id' => $officeManaged->id]);
        $this->salesRep->update(['office_id' => $officeManaged->id]);

        $this->actingAs($this->setter);

        Livewire::test(RegionRow::class, $this->buildProps($region))
            ->call('collapseRegion')
            ->assertSet('offices.0.name', $officeManaged->name)
            ->assertNotSet('offices.1.name', $officeNotManaged->name);

        $this->actingAs($this->salesRep);

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
