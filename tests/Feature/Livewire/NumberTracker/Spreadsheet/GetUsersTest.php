<?php

namespace Tests\Feature\Livewire\NumberTracker\Spreadsheet;

use App\Enum\Role;
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
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $ann  = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($mary->id);

        /** @var Office $office01 */
        $office01 = Office::factory()->create(['region_id' => $region->id]);
        $office01->managers()->attach($ann->id);

        /** @var Office $office02 */
        $office02 = Office::factory()->create(['region_id' => $region->id]);
        $office02->managers()->attach($ann->id);

        User::factory()->times(10)->create([
            'role'      => Role::SETTER,
            'office_id' => $office01->id,
        ]);
        User::factory()->times(10)->create([
            'role'      => Role::SETTER,
            'office_id' => $office02->id,
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
            ->where('office_id', $office->id)
            ->get()
            ->map(function (User $user) {
                $user->dailyNumbers = $user->dailyNumbers->groupBy(function (DailyNumber $dailyNumber) {
                    return (new Carbon($dailyNumber->date))->format('F dS');
                });

                return $user;
            });
    }
}
