<?php

namespace Tests\Feature\NumberTracker\Spreadsheet;

use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetSpreadsheetTest extends TestCase
{
    use RefreshDatabase;

    private User $regionManager;

    private User $departmentManager;

    private User $officeManager;

    public function setUp():void
    {
        parent::setUp();

        $this->regionManager     = User::factory()->create(['role' => 'Region Manager']);
        $this->officeManager     = User::factory()->create(['role' => 'Office Manager']);
        $this->departmentManager = User::factory()->create(['role' => 'Department Manager']);
    }

    /** @test */
    public function it_should_prevent_a_setter_see_the_page()
    {
        $john = User::factory()->create(['role' => 'Setter']);

        $this
            ->actingAs($john)
            ->get(route('number-tracking.spreadsheet'))
            ->assertNotFound();
    }

    /** @test */
    public function it_should_prevent_a_sales_rep_see_the_page()
    {
        $john = User::factory()->create(['role' => 'Sales Rep']);

        $this
            ->actingAs($john)
            ->get(route('number-tracking.spreadsheet'))
            ->assertNotFound();
    }

    /** @test */
    public function it_should_allow_an_admin_see_the_page()
    {
        $john       = User::factory()->create(['role' => 'Admin']);
        $department = Department::factory()->create();

        $this->makeRegionAndOffice($department, $this->regionManager, $this->officeManager);

        $this
            ->actingAs($john)
            ->get(route('number-tracking.spreadsheet'))
            ->assertOk();
    }

    /** @test */
    public function it_should_allow_an_owner_see_the_page()
    {
        $john       = User::factory()->create(['role' => 'Owner']);
        $department = Department::factory()->create();

        $this->makeRegionAndOffice($department, $this->regionManager, $this->officeManager);

        $this
            ->actingAs($john)
            ->get(route('number-tracking.spreadsheet'))
            ->assertOk();
    }

    /** @test */
    public function it_should_allow_a_department_manager_see_the_page()
    {
        $department = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);

        $this->departmentManager->update(['department_id' => $department->id]);

        $this->makeRegionAndOffice($department, $this->regionManager, $this->officeManager);

        $this
            ->actingAs($this->departmentManager)
            ->get(route('number-tracking.spreadsheet'))
            ->assertOk();
    }

    /** @test */
    public function it_should_allow_a_region_manager_see_the_page()
    {
        $department = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);

        $this->departmentManager->update(['department_id' => $department->id]);

        $this->makeRegionAndOffice($department, $this->regionManager, $this->officeManager);

        $this
            ->actingAs($this->regionManager)
            ->get(route('number-tracking.spreadsheet'))
            ->assertOk();
    }

    /** @test */
    public function it_should_allow_a_office_manager_see_the_page()
    {
        $department = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);

        $this->departmentManager->update(['department_id' => $department->id]);

        $this->makeRegionAndOffice($department, $this->regionManager, $this->officeManager);

        $this
            ->actingAs($this->officeManager)
            ->get(route('number-tracking.spreadsheet'))
            ->assertOk();
    }

    private function makeRegionAndOffice(Department $department, User $regionManager, User $officeManager)
    {
        $region = Region::factory()->create([
            'department_id'     => $department->id,
            'region_manager_id' => $regionManager->id,
        ]);

        Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);
    }
}