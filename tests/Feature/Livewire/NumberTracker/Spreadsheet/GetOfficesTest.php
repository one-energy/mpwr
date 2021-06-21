<?php

namespace Tests\Feature\Livewire\NumberTracker\Spreadsheet;

use App\Http\Livewire\NumberTracker\Spreadsheet;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GetOfficesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_all_offices_if_authenticated_user_has_admin_role()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Office Manager']);

        $region = Region::factory()->create(['region_manager_id' => $mary->id]);

        $offices = Office::factory()->times(10)->create([
            'region_id'         => $region->id,
            'office_manager_id' => $ann->id
        ]);

        $this->actingAs($john);

        $component = Livewire::test(Spreadsheet::class)
            ->assertHasNoErrors()
            ->assertSet('offices', Office::oldest('name')->get());

        $offices->each(fn(Office $office) => $component->assertSee($office->name));
    }

    /** @test */
    public function it_should_return_all_offices_related_to_the_department_when_user_has_department_manager_role()
    {
        $john = User::factory()->create(['role' => 'Department Manager']);
        $zack = User::factory()->create(['role' => 'Department Manager']);
        $mary = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Office Manager']);

        $department01 = Department::factory()->create(['department_manager_id' => $john->id]);
        $john->update(['department_id' => $department01->id]);

        $region01  = Region::factory()->create([
            'region_manager_id' => $mary->id,
            'department_id'     => $department01->id
        ]);
        $offices01 = Office::factory()->times(10)->create([
            'region_id'         => $region01->id,
            'office_manager_id' => $ann->id
        ]);

        $department02 = Department::factory()->create(['department_manager_id' => $zack->id]);
        $region02     = Region::factory()->create([
            'region_manager_id' => $mary->id,
            'department_id'     => $department02->id
        ]);
        $offices02    = Office::factory()->times(10)->create([
            'region_id'         => $region02->id,
            'office_manager_id' => $ann->id
        ]);

        $this->actingAs($john);

        $component = Livewire::test(Spreadsheet::class)
            ->assertHasNoErrors()
            ->assertSet('selectedOffice', $offices01->sortBy('name', SORT_NATURAL)->first()->id);

        $offices01->each(fn(Office $office) => $component->assertSee($office->name));
        $offices02->each(fn(Office $office) => $component->assertDontSee($office->name));
    }

    /** @test */
    public function it_should_return_all_offices_from_managed_regions_if_authenticated_user_has_region_manager_role()
    {
        $john = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Region Manager']);

        $mary = User::factory()->create(['role' => 'Office Manager']);
        $doe  = User::factory()->create(['role' => 'Office Manager']);

        $region01 = Region::factory()->create(['region_manager_id' => $john->id]);
        $region02 = Region::factory()->create(['region_manager_id' => $ann->id]);

        Office::factory()->times(2)->create(['region_id' => $region01->id, 'office_manager_id' => $mary->id]);
        Office::factory()->times(10)->create(['region_id' => $region02->id, 'office_manager_id' => $doe->id]);

        $this->actingAs($john);

        $officesFromRegion01 = Office::query()
            ->oldest('name')
            ->whereIn('region_id', $john->managedRegions->pluck('id'))
            ->get();

        $officesFromRegion02 = Office::query()
            ->oldest('name')
            ->whereIn('region_id', $ann->managedRegions->pluck('id'))
            ->get();

        $component = Livewire::test(Spreadsheet::class)
            ->assertHasNoErrors()
            ->assertSet('offices', $officesFromRegion01)
            ->assertNotSet('offices', $officesFromRegion02);

        $officesFromRegion01->each(fn(Office $office) => $component->assertSee($office->name));
        $officesFromRegion02->each(fn(Office $office) => $component->assertDontSee($office->name));
    }

    /** @test */
    public function it_should_return_all_managed_offices_if_authenticated_user_has_office_manager_role()
    {
        $john = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Region Manager']);

        $mary = User::factory()->create(['role' => 'Office Manager']);
        $doe  = User::factory()->create(['role' => 'Office Manager']);

        $region01 = Region::factory()->create(['region_manager_id' => $john->id]);
        $region02 = Region::factory()->create(['region_manager_id' => $ann->id]);

        Office::factory()->times(2)->create(['region_id' => $region01->id, 'office_manager_id' => $mary->id]);
        Office::factory()->times(10)->create(['region_id' => $region02->id, 'office_manager_id' => $doe->id]);

        $this->actingAs($john);

        $officesFromRegion01 = Office::query()
            ->oldest('name')
            ->whereIn('id', $mary->managedOffices->pluck('id'))
            ->get();

        $officesFromRegion02 = Office::query()
            ->oldest('name')
            ->whereIn('id', $doe->managedOffices->pluck('id'))
            ->get();

        $component = Livewire::test(Spreadsheet::class)
            ->assertHasNoErrors()
            ->assertSet('offices', $officesFromRegion01)
            ->assertNotSet('offices', $officesFromRegion02);

        $officesFromRegion01->each(fn(Office $office) => $component->assertSee($office->name));
        $officesFromRegion02->each(fn(Office $office) => $component->assertDontSee($office->name));
    }

    /** @test */
    public function it_should_return_an_empty_collection_if_the_department_has_no_office_related()
    {
        $john = User::factory()->create(['role' => 'Department Manager']);

        $this->actingAs($john);

        Livewire::test(Spreadsheet::class)
            ->assertHasNoErrors()
            ->assertSet('offices', collect())
            ->assertSet('selectedOffice', 0);
    }
}
