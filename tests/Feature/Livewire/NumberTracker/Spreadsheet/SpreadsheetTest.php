<?php

namespace Tests\Feature\Livewire\NumberTracker\Spreadsheet;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\Spreadsheet;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Builders\OfficeBuilder;
use Tests\Builders\RegionBuilder;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class SpreadsheetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_see_the_day_of_the_start_week()
    {
        $john = UserBuilder::build(['role' => Role::ADMIN])->save()->get();

        $departmentManager = UserBuilder::build(['role' => Role::DEPARTMENT_MANAGER])->save()->get();
        $regionManager     = UserBuilder::build(['role' => Role::REGION_MANAGER])->save()->get();
        $officeManager     = UserBuilder::build(['role' => Role::OFFICE_MANAGER])->save()->get();

        $department = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $region     = RegionBuilder::build()->withManager($regionManager)->withDepartment($department)->save()->get();

        OfficeBuilder::build()->withManager($officeManager)->region($region)->save()->get();
        OfficeBuilder::build()->withManager($officeManager)->region($region)->save()->get();
        OfficeBuilder::build()->withManager($officeManager)->region($region)->save()->get();

        $this->actingAs($john);
        $firstDayOfWeekLabel = today()->format('D dS');

        Livewire::test(Spreadsheet::class)
            ->assertHasNoErrors()
            ->assertSee($firstDayOfWeekLabel);
    }

    /** @test */
    public function it_should_see_all_months_until_current_month()
    {
        $john = UserBuilder::build(['role' => Role::ADMIN])->save()->get();
        
        $months = [];

        $this->actingAs($john);

        for ($index = 1; $index <= Carbon::now()->month; $index++) {
            $months[$index] = Carbon::create(month: $index)->monthName;    
        }
        
        Livewire::test(Spreadsheet::class)
            ->assertHasNoErrors()
            ->assertSeeInOrder($months);
    }

}
