<?php

namespace Tests\Feature\Livewire\Castle\Office;

use App\Http\Livewire\Castle\Offices;
use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DestroyOfficeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_soft_delete_a_office()
    {
        $john = User::factory()->create(['role' => 'Admin']);

        $office = Office::factory()->create();

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
        $john   = User::factory()->create(['role' => 'Admin']);
        $office = Office::factory()->create();

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
        $john   = User::factory()->create(['role' => 'Admin']);
        $office = Office::factory()->create();

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

        $dummyUsers->each(fn (User $user) => $this->assertSoftDeleted($user));
    }

    /** @test */
    public function it_should_detach_managers_on_destroy()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $ann  = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Office $office */
        $office = Office::factory()->create();
        $office->managers()->attach([$mary->id, $ann->id]);

        $mary->update(['office_id' => $office->id]);
        $ann->update(['office_id' => $office->id]);

        $this->assertSame($office->id, $mary->office_id);
        $this->assertSame($office->id, $ann->office_id);
        $this->assertDatabaseCount('user_managed_offices', 2);

        $this->actingAs($john);

        Livewire::test(Offices::class)
            ->call('setDeletingOffice', $office)
            ->set('deletingName', $office->name)
            ->call('destroy')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('user_managed_offices', 0);
        $this->assertSoftDeleted($office->getTable(), [
            'id' => $office->id,
        ]);
    }
}
