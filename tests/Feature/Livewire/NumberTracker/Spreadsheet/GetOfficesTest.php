<?php

namespace Tests\Feature\Livewire\NumberTracker\Spreadsheet;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\Spreadsheet;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\TestCase;

class GetOfficesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_all_offices_if_authenticated_user_has_admin_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $ann  = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($mary->id);

        /** @var Collection|Office[] $offices */
        $offices = Office::factory()->times(10)->create(['region_id' => $region->id]);
        $offices->each(fn(Office $office) => $office->managers()->attach($ann->id));

        $this->actingAs($john);

        $component = Livewire::test(Spreadsheet::class)
            ->assertHasNoErrors()
            ->assertSet('offices', Office::oldest('name')->get());

        $offices->each(fn(Office $office) => $component->assertSee($office->name));
    }

    /** @test */
    public function it_should_return_all_offices_related_to_the_department_when_user_has_department_manager_role()
    {
        $john = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $zack = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $mary = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $ann  = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Department $department01 */
        $department01 = Department::factory()->create();
        $department01->managers()->attach($john->id);
        $john->update(['department_id' => $department01->id]);

        /** @var Region $region01 */
        $region01 = Region::factory()->create(['department_id' => $department01->id]);
        $region01->managers()->attach($mary->id);

        /** @var Collection|Office[] $offices01 */
        $offices01 = Office::factory()->times(10)->create(['region_id' => $region01->id]);
        $offices01->each(fn(Office $office) => $office->managers()->attach($ann->id));

        /** @var Department $department02 */
        $department02 = Department::factory()->create();
        $department02->managers()->attach($mary->id);

        /** @var Region $region02 */
        $region02 = Region::factory()->create(['department_id' => $department02->id]);
        $region02->managers()->attach($mary->id);

        /** @var Collection|Office[] $offices02 */
        $offices02 = Office::factory()->times(10)->create(['region_id' => $region02->id]);
        $offices02->each(fn(Office $office) => $office->managers()->attach($ann->id));

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
        $john = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $ann  = User::factory()->create(['role' => Role::REGION_MANAGER]);

        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $doe  = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Region $region01 */
        $region01 = Region::factory()->create();
        $region01->managers()->attach($john->id);

        /** @var Region $region02 */
        $region02 = Region::factory()->create();
        $region02->managers()->attach($ann->id);

        Office::factory()->times(2)
            ->create(['region_id' => $region01->id])
            ->each(fn(Office $office) => $office->managers()->attach($mary->id));

        Office::factory()->times(10)
            ->create(['region_id' => $region02->id])
            ->each(fn(Office $office) => $office->managers()->attach($doe->id));

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
        $john = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $ann  = User::factory()->create(['role' => Role::REGION_MANAGER]);

        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $doe  = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Region $region01 */
        $region01 = Region::factory()->create();
        $region01->managers()->attach($john->id);

        /** @var Region $region02 */
        $region02 = Region::factory()->create();
        $region02->managers()->attach($ann->id);

        Office::factory()->times(2)
            ->create(['region_id' => $region01->id])
            ->each(fn(Office $office) => $office->managers()->attach($mary->id));

        Office::factory()->times(10)
            ->create(['region_id' => $region02->id])
            ->each(fn(Office $office) => $office->managers()->attach($doe->id));

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
        $john = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        $this->actingAs($john);

        Livewire::test(Spreadsheet::class)
            ->assertHasNoErrors()
            ->assertSet('offices', collect())
            ->assertSet('selectedOffice', 0);
    }
}
