<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Scoreboard;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\User;
use App\Enum\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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
}
