<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\UserRow;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserRowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_show_icon_when_user_is_deleted()
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $regionManager     = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $officeManager     = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create(['department_manager_id' => $departmentManager->id]);

        /** @var Office $office */
        $office = Office::factory()->create([
            'office_manager_id' => $officeManager->id,
            'region_id'         => Region::factory()->create([
                'region_manager_id' => $regionManager->id,
                'department_id'     => $department->id,
            ])->id,
        ]);

        /** @var User $user */
        $user = User::factory()->create([
            'role'          => Role::SALES_REP,
            'department_id' => $department->id,
            'office_id'     => $office->id,
            'deleted_at'    => Carbon::now(),
        ]);

        $dailyNumbers = DailyNumber::factory()->times(2)->create([
            'user_id'   => $user->id,
            'office_id' => $office->id,
            'date'      => Carbon::yesterday(),
            'doors'     => 15,
        ]);

        $this->actingAs($departmentManager);

        Livewire::test(UserRow::class, [
            'user'             => $user,
            'userDailyNumbers' => $dailyNumbers
        ])
            ->assertSeeHtml('<path d="M354.315,318.023v-23.922c0-41.355-33.645-75-75-75H162.326c-41.355,0-75,33.645-75,75v146.99c0,24.813,20.187,45,45,45    h126.48C276.434,502.176,299.868,512,325.556,512c54.654,0,99.118-44.464,99.118-99.119    C424.674,368.226,394.988,330.379,354.315,318.023z M236.363,456.09H132.326c-8.271,0.001-15-6.728-15-14.999v-146.99    c0-24.813,20.187-45,45-45h116.989c24.813,0,45,20.187,45,45v19.678c-54.084,0.668-97.878,44.863-97.878,99.102    C226.437,428.362,230.007,443.024,236.363,456.09z M256.438,412.882c0-38.112,31.006-69.118,69.118-69.118    c13.637,0,26.353,3.986,37.076,10.831l-95.364,95.362C260.424,439.234,256.438,426.519,256.438,412.882z M325.556,482    c-13.637,0-26.353-3.986-37.075-10.83l95.363-95.363c6.844,10.722,10.83,23.438,10.83,37.074    C394.674,450.994,363.668,482,325.556,482z" fill="#46a049" data-original="#000000" style="" class=""/>');
    }
}
