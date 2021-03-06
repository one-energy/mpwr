<?php

namespace Tests\Feature\Livewire\Castle\Office;

use App\Http\Livewire\Castle\Offices;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\TestCase;

class DestroyOfficeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_soft_delete_a_office()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Office Manager']);

        $region = Region::factory()->create([
            'department_id'     => Department::factory()->create(),
            'region_manager_id' => $mary->id
        ]);

        $office = Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $ann->id
        ]);

        $this->actingAs($john);

        Livewire::test(Offices::class)
            ->set('deletingName', $office->name)
            ->call('setDeletingOffice', $office)
            ->call('destroy');

        $this->assertSoftDeleted($office);
    }

    /** @test */
    public function it_should_soft_delete_daily_numbers_when_delete_an_office()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Office Manager']);

        $region = Region::factory()->create([
            'department_id'     => Department::factory()->create(),
            'region_manager_id' => $mary->id
        ]);

        $office = Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $ann->id
        ]);

        $mary         = User::factory()->create([
            'role'      => 'Setter',
            'office_id' => $office->id,
        ]);
        $dailyNumbers = DailyNumber::factory()
            ->times(2)
            ->create(['user_id' => $mary->id]);

        $dummyUser    = User::factory()->create(['role' => 'Setter']);
        $dummyNumbers = DailyNumber::factory()
            ->times(2)
            ->create(['user_id' => $dummyUser->id]);

        $this->actingAs($john);

        Livewire::test(Offices::class)
            ->set('deletingName', $office->name)
            ->call('setDeletingOffice', $office)
            ->call('destroy');

        $this->assertSoftDeleted($office);

        $dailyNumbers->each(fn(DailyNumber $dailyNumber) => $this->assertSoftDeleted($dailyNumber));
        $dummyNumbers->each(fn(DailyNumber $dailyNumber) => $this->assertNull($dailyNumber->deleted_at));
    }

    /** @test */
    public function it_should_soft_delete_all_users_from_the_office_that_is_being_deleted()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Region Manager']);
        $ann  = User::factory()->create(['role' => 'Office Manager']);

        $region = Region::factory()->create([
            'department_id'     => Department::factory()->create(),
            'region_manager_id' => $mary->id
        ]);

        $office = Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $ann->id
        ]);

        /** @var Collection */
        $dummyUsers = User::factory()
            ->times(5)
            ->create(['office_id' => $office->id]);

        $this->actingAs($john);

        Livewire::test(Offices::class)
            ->set('deletingName', $office->name)
            ->call('setDeletingOffice', $office)
            ->call('destroy');

        $this->assertSoftDeleted($office);

        $dummyUsers->each(fn(User $user) => $this->assertSoftDeleted($user));
    }
}
