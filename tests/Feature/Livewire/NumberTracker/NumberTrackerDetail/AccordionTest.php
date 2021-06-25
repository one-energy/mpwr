<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\NumberTrackerDetailAccordionTable;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class AccordionTest extends TestCase
{
    use DatabaseTransactions;

    public Department $department;

    public User $regionManager;

    public User $officeManager;

    public User $salesRep;

    public User $setter;

    public function setUp(): void
    {
        parent::setUp();

        $this->department = Department::factory()->create();

        $this->regionManager = User::factory()->create([
            'role'          => Role::REGION_MANAGER,
            'department_id' => $this->department
        ]);
        $this->officeManager = User::factory()->create([
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $this->department
        ]);
        $this->salesRep      = User::factory()->create([
            'role'          => Role::SALES_REP,
            'department_id' => $this->department
        ]);
        $this->setter        = User::factory()->create([
            'role'          => Role::SETTER,
            'department_id' => $this->department
        ]);
    }

    /** @test */
    public function it_should_just_show_all_regions_of_department()
    {
        $regionManaged = Region::factory()->create([
            'region_manager_id' => $this->regionManager,
            'department_id'     => $this->department
        ]);

        $regioneNotManaged = Region::factory()->create([
            'region_manager_id' => User::factory()->create(['role' => Role::REGION_MANAGER]),
            'department_id'     => $this->department
        ]);

        $this->actingAs($this->regionManager);

        Livewire::test(NumberTrackerDetailAccordionTable::class, $this->buildProps())
            ->assertSee($regionManaged->name)
            ->assertSee($regioneNotManaged->name);
    }

    /** @test */
    public function it_should_just_show_region_of_office_managed_by_user()
    {
        $regionOfOffice = Region::factory()->create([
            'region_manager_id' => $this->regionManager,
            'department_id'     => $this->department
        ]);

        $regionAny = Region::factory()->create([
            'region_manager_id' => User::factory()->create(['role' => Role::REGION_MANAGER]),
        ]);

        Office::factory()->create([
            'office_manager_id' => $this->officeManager,
            'region_id'         => $regionOfOffice
        ]);

        $this->actingAs($this->officeManager);

        Livewire::test(NumberTrackerDetailAccordionTable::class, $this->buildProps())
            ->assertSee($regionOfOffice->name)
            ->assertDontSee($regionAny->name);
    }

    /** @test */
    public function it_should_just_show_region_of_office_of_setter_or_sales_rep()
    {
        $regionOfOffice = Region::factory()->create([
            'region_manager_id' => $this->regionManager,
            'department_id'     => $this->department
        ]);

        $regionAny = Region::factory()->create([
            'region_manager_id' => User::factory()->create(['role' => Role::REGION_MANAGER]),
        ]);

        $office = Office::factory()->create([
            'office_manager_id' => $this->officeManager,
            'region_id'         => $regionOfOffice
        ]);

        $this->setter->office_id = $office->id;
        $this->setter->save();

        $this->salesRep->office_id = $office->id;
        $this->salesRep->save();

        $this->actingAs($this->setter);

        Livewire::test(NumberTrackerDetailAccordionTable::class, $this->buildProps())
            ->assertSee($regionOfOffice->name)
            ->assertDontSee($regionAny->name);

        $this->actingAs($this->salesRep);

        Livewire::test(NumberTrackerDetailAccordionTable::class, $this->buildProps())
            ->assertSee($regionOfOffice->name)
            ->assertDontSee($regionAny->name);
    }

    private function buildProps(): array
    {
        return [
            'deleteds'     => false,
            'period'       => 'd',
            'selectedDate' => today()
        ];
    }
}
