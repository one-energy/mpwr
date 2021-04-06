<?php

namespace Tests\Feature\Livewire\Castle\Office;

use App\Http\Livewire\Castle\Offices;
use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\User;
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
}
