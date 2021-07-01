<?php

namespace Tests\Feature\Livewire;

use App\Enum\Role;
use App\Http\Livewire\Scoreboard;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Builders\OfficeBuilder;
use Tests\TestCase;

class ScoreboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_not_show_users_from_another_department_in_scoring()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        [$setter01, $setter02, $setter03, $setter04] = User::factory()->times(4)->create(['role' => Role::SETTER]);

        $department01 = Department::factory()->create();
        $department02 = Department::factory()->create();

        collect([$setter01, $setter02, $john])
            ->each(fn(User $user) => $user->update(['department_id' => $department01->id]));

        collect([$setter03, $setter04])
            ->each(fn(User $user) => $user->update(['department_id' => $department02->id]));

        DailyNumber::factory()->create([
            'user_id' => $setter01->id,
            'doors'   => 10,
            'date'    => now()->format('Y-m-d'),
        ]);
        DailyNumber::factory()->create([
            'user_id' => $setter02->id,
            'doors'   => 20,
            'date'    => now()->format('Y-m-d'),
        ]);
        DailyNumber::factory()->create([
            'user_id' => $setter03->id,
            'doors'   => 10,
            'date'    => now()->format('Y-m-d'),
        ]);
        DailyNumber::factory()->create([
            'user_id' => $setter04->id,
            'doors'   => 10,
            'date'    => now()->format('Y-m-d'),
        ]);

        $this->actingAs($john);

        Livewire::test(Scoreboard::class)
            ->assertHasNoErrors()
            ->assertViewIs('livewire.scoreboard')
            ->assertSee($setter01->full_name)
            ->assertSee($setter02->full_name)
            ->assertDontSee($setter03->full_name)
            ->assertDontSee($setter04->full_name);
    }

    /** @test */
    public function it_should_show_top_ten_doors_on_day_period()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        [$setter01, $setter02] = User::factory()->times(2)->create(['role' => Role::SETTER]);

        $department = Department::factory()->create();

        collect([$setter01, $setter02, $john])
            ->each(fn(User $user) => $user->update(['department_id' => $department->id]));

        $today = today();

        DailyNumber::factory()->times(10)->create([
            'user_id' => $setter01->id,
            'doors'   => 10,
            'date'    => $today->format('Y-m-d'),
        ]);
        DailyNumber::factory()->times(10)->create([
            'user_id' => $setter02->id,
            'doors'   => 10,
            'date'    => $today->addDay()->format('Y-m-d'),
        ]);

        $this->actingAs($john);

        Livewire::test(Scoreboard::class)
            ->assertSet('period', 'd')
            ->assertSee($setter01->full_name)
            ->assertDontSee($setter02->full_name);
    }

    /** @test */
    public function it_should_be_possible_set_date()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($john);

        $future = today()->addDay();

        Livewire::test(Scoreboard::class, ['date' => today()])
            ->call('setDate', $future->toDateString())
            ->assertSet('date', $future)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_be_possible_set_period()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($john);

        Livewire::test(Scoreboard::class)
            ->assertSet('period', 'd')
            ->call('setPeriod', 'dd')
            ->assertSet('period', 'd')
            ->assertHasNoErrors();

        Livewire::test(Scoreboard::class)
            ->assertSet('period', 'd')
            ->call('setPeriod', 'w')
            ->assertSet('period', 'w')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_be_possible_set_user()
    {
        $john   = User::factory()->create(['role' => Role::ADMIN]);
        $office = OfficeBuilder::build()->withManager()->region()->save()->get();

        $ann = $office->officeManager;

        $this->actingAs($john);

        Livewire::test(Scoreboard::class)
            ->call('setUser', $ann->id)
            ->assertSet('user.full_name', $ann->full_name)
            ->assertSet('userArray', [
                'photo_url'   => $ann->photo_url,
                'full_name'   => $ann->full_name,
                'office_name' => $ann->office->name,
            ])
            ->assertDispatchedBrowserEvent('setUserNumbers')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_calculate_daily_numbers_when_set_user()
    {
        $john   = User::factory()->create(['role' => Role::ADMIN]);
        $office = OfficeBuilder::build()->withManager()->region()->save()->get();

        $ann = $office->officeManager;

        DailyNumber::factory()->create([
            'user_id'       => $ann->id,
            'office_id'     => $ann->office_id,
            'sets'          => 1,
            'sats'          => 1,
            'doors'         => 1,
            'hours_knocked' => 1,
            'closes'        => 1,
            'closer_sits'   => 1
        ]);

        $this->actingAs($john);

        Livewire::test(Scoreboard::class)
            ->call('setUser', $ann->id)
            ->assertSet('dpsRatio', 1)
            ->assertSet('hpsRatio', 1.0)
            ->assertSet('sitRatio', 1)
            ->assertSet('closeRatio', 1)
            ->assertHasNoErrors();
    }
}
