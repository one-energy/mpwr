<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Http\Livewire\NumberTracker\TotalOverview;
use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\Builders\OfficeBuilder;
use Tests\Builders\RegionBuilder;
use Tests\TestCase;

class TotalOverviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_set_date_or_period()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($john);

        Livewire::test(TotalOverview::class)
            ->emit('setDateOrPeriod', today()->format('Y-m-d'), 'w')
            ->assertSet('period', 'w')
            ->assertSet('date', today());
    }

    /** @test */
    public function it_should_be_possible_update_numbers()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($john);

        Livewire::test(TotalOverview::class)
            ->set('users', [1, 2, 3])
            ->set('offices', [1, 2, 3])
            ->assertSet('withTrashed', false)
            ->emit('updateNumbers', [
                'users'       => [1, 1, 1, 3, 4],
                'offices'     => [4, 4, 4, 3, 4],
                'withTrashed' => true
            ])
            ->assertSet('users', [1, 3, 4])
            ->assertSet('offices', [4, 3])
            ->assertSet('withTrashed', true);
    }

    /** @test */
    public function it_should_return_daily_numbers_from_the_selected_offices_users_and_period()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($john);

        $this->createDailyNumbers(10, [
            'date' => today()->format('Y-m-d')
        ]);

        /** @var Collection $users */
        $users = User::query()->whereNotIn('id', [$john->id])->get();

        /** @var Collection $offices */
        $offices = Office::all();

        $dailyNumbers = DailyNumber::query()
            ->whereIn('user_id', $users->pluck('id')->toArray())
            ->whereIn('office_id', $offices->pluck('id')->toArray())
            ->inPeriod('d', today())
            ->get();

        Livewire::test(TotalOverview::class)
            ->set('period', 'd')
            ->set('date', today())
            ->set('users', $users->pluck('id')->toArray())
            ->set('offices', $offices->pluck('id')->toArray())
            ->assertSet('dailyNumbers', $dailyNumbers);
    }

    /** @test */
    public function it_should_return_daily_numbers_from_the_selected_offices_users_and_period_with_trashed()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($john);

        $this->createDailyNumbers(10, [
            'date' => today()->format('Y-m-d')
        ]);

        /** @var Collection $users */
        $users = User::query()->whereNotIn('id', [$john->id])->get();

        /** @var Collection $offices */
        $offices = Office::all();

        $dailyNumbers = DailyNumber::query()
            ->whereIn('user_id', $users->pluck('id')->toArray())
            ->whereIn('office_id', $offices->pluck('id')->toArray())
            ->inPeriod('d', today())
            ->get();

        $dailyNumbers->first()->delete();

        Livewire::test(TotalOverview::class)
            ->set('period', 'd')
            ->set('date', today())
            ->set('withTrashed', true)
            ->set('users', $users->pluck('id')->toArray())
            ->set('offices', $offices->pluck('id')->toArray())
            ->assertSet('dailyNumbers', $dailyNumbers);
    }

    /** @test */
    public function it_should_return_last_daily_numbers_from_the_selected_offices_and_period()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($john);

        $this->createDailyNumbers(10, [
            'date' => today()->subMonth()->format('Y-m-d')
        ]);

        /** @var Collection $users */
        $users = User::query()->whereNotIn('id', [$john->id])->get();

        /** @var Collection $offices */
        $offices = Office::all();

        $dailyNumbers = DailyNumber::query()
            ->whereIn('user_id', $users->pluck('id')->toArray())
            ->whereIn('office_id', $offices->pluck('id')->toArray())
            ->inLastPeriod('m', today())
            ->get();

        Livewire::test(TotalOverview::class)
            ->set('period', 'm')
            ->set('date', today())
            ->set('users', $users->pluck('id')->toArray())
            ->set('offices', $offices->pluck('id')->toArray())
            ->assertSet('lastDailyNumbers', $dailyNumbers);
    }

    /** @test */
    public function it_should_return_last_daily_numbers_from_the_selected_offices_and_period_with_trashed()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($john);

        $this->createDailyNumbers(10, [
            'date' => today()->subMonth()->format('Y-m-d')
        ]);

        /** @var Collection $users */
        $users = User::query()->whereNotIn('id', [$john->id])->get();

        /** @var Collection $offices */
        $offices = Office::all();

        $dailyNumbers = DailyNumber::query()
            ->whereIn('user_id', $users->pluck('id')->toArray())
            ->whereIn('office_id', $offices->pluck('id')->toArray())
            ->inLastPeriod('m', today())
            ->get();

        $dailyNumbers->first()->delete();

        Livewire::test(TotalOverview::class)
            ->set('period', 'm')
            ->set('date', today())
            ->set('withTrashed', true)
            ->set('users', $users->pluck('id')->toArray())
            ->set('offices', $offices->pluck('id')->toArray())
            ->assertSet('lastDailyNumbers', $dailyNumbers);
    }

    private function createDailyNumbers(int $quantity, $attributes = [])
    {
        $mary = User::factory()->create(['role' => 'Office Manager']);
        $ann  = User::factory()->create(['role' => 'Region Manager']);

        $region = RegionBuilder::make()->withManager($ann)->save()->get();

        $office = OfficeBuilder::make()
            ->withManager($mary)
            ->region($region)
            ->save()
            ->get();

        /** @var Collection $users */
        $users = User::factory()->times($quantity)->create([
            'role'      => 'Sales Rep',
            'office_id' => $office->id
        ]);

        $users->each(function (User $user) use ($attributes) {
            DailyNumber::factory()->create(array_merge([
                'user_id'   => $user->id,
                'office_id' => $user->office_id
            ], $attributes));
        });
    }
}
