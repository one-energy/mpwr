<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Http\Livewire\NumberTracker\NumberTrackerDetail;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\User;
use App\Enum\Role;
use Livewire\Livewire;
use Tests\Feature\FeatureTest;

class GetTopTenTrackersTest extends FeatureTest
{
    /** @test */
    public function it_should_be_possible_order_by_doors()
    {
        $john       = User::factory()->create(['role' => Role::ADMIN]);
        $department = Department::factory()->create();

        $setter01 = User::factory()->create([
            'department_id' => $department->id,
            'role'          => Role::SETTER,
        ]);
        $setter02 = User::factory()->create([
            'department_id' => $department->id,
            'role'          => Role::SETTER,
        ]);

        DailyNumber::factory()->create([
            'user_id' => $setter01->id,
            'date'    => now()->toDateString(),
            'doors'   => 1,
        ]);
        DailyNumber::factory()->create([
            'user_id' => $setter02->id,
            'date'    => now()->toDateString(),
            'doors'   => 2,
        ]);

        $this->actingAs($john);

        Livewire::test(NumberTrackerDetail::class)
            ->set('selectedPill', 'doors')
            ->assertSet('selectedPill', 'doors')
            ->assertCount('topTenTrackers', 2)
            ->assertSeeInOrder([$setter02->full_name, $setter01->full_name]);
    }

    /** @test */
    public function it_should_be_possible_order_by_sats()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($mary->id);

        $setter01 = User::factory()->create([
            'role'          => Role::SALES_REP,
            'department_id' => $department->id,
        ]);
        $setter02 = User::factory()->create([
            'role'          => Role::SALES_REP,
            'department_id' => $department->id,
        ]);

        DailyNumber::factory()->create([
            'user_id' => $setter01->id,
            'date'    => now()->toDateString(),
            'sits'    => 2,
            'sats'    => 2,
        ]);
        DailyNumber::factory()->create([
            'user_id' => $setter02->id,
            'date'    => now()->toDateString(),
            'sits'    => 1,
            'sats'    => 1,
        ]);

        $this->actingAs($john);

        Livewire::test(NumberTrackerDetail::class, ['selectedDepartment' => $department->id])
            ->set('selectedPill', 'sats')
            ->assertSet('selectedPill', 'sats')
            ->assertCount('topTenTrackers', 2)
            ->assertSeeInOrder([$setter01->full_name, $setter02->full_name]);
    }
}
