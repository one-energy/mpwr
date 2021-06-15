<?php

namespace Tests\Feature\Livewire\NumberTracker\Spreadsheet;

use App\Http\Livewire\NumberTracker\Spreadsheet;
use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\TestCase;

class GetUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_users_from_selected_office()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Office Manager']);

        $region   = Region::factory()->create(['region_manager_id' => $mary->id]);
        $office01 = Office::factory()->create(['region_id' => $region->id, 'office_manager_id' => $ann->id]);
        $office02 = Office::factory()->create(['region_id' => $region->id, 'office_manager_id' => $ann->id]);

        User::factory()->times(10)->create([
            'role'      => 'Setter',
            'office_id' => $office01->id
        ]);
        User::factory()->times(10)->create([
            'role'      => 'Setter',
            'office_id' => $office02->id
        ]);

        $this->actingAs($john);

        $settersFromOffice01 = $this->getUsersGroupedByDailyNumbers($office01);
        $settersFromOffice02 = $this->getUsersGroupedByDailyNumbers($office02);

        $component = Livewire::test(Spreadsheet::class)
            ->assertHasNoErrors()
            ->set('selectedOffice', $office01->id)
            ->assertSet('users', $settersFromOffice01);

        $settersFromOffice01->each(fn(User $user) => $component->assertSee($user->full_name));
        $settersFromOffice02->each(fn(User $user) => $component->assertDontSee($user->full_name));
    }

    private function getUsersGroupedByDailyNumbers(Office $office): Collection
    {
        return User::query()
            ->with('dailyNumbers')
            ->where('office_id', $office->id)->get()
            ->map(function (User $user) {
                $user->dailyNumbers = $user->dailyNumbers->groupBy(function (DailyNumber $dailyNumber) {
                    return (new Carbon($dailyNumber->date))->format('F dS');
                });

                return $user;
            });
    }
}
