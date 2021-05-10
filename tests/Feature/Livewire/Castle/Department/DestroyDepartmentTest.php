<?php

namespace Tests\Feature\Livewire\Castle\Department;

use App\Http\Livewire\Castle\Departments;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\TestCase;

class DestroyDepartmentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'Admin']);
    }

    /** @test */
    public function it_should_soft_delete_a_department()
    {
        $mary       = User::factory()->create(['role' => 'Department Manager']);
        $department = Department::factory()->create([
            'department_manager_id' => $mary->id,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(Departments::class)
            ->call('setDeletingDepartment', $department)
            ->call('destroy');

        $this->assertSoftDeleted($department);
    }

    /** @test */
    public function it_should_soft_delete_regions_when_delete_a_department()
    {
        $mary       = User::factory()->create(['role' => 'Department Manager']);
        $department = Department::factory()->create([
            'department_manager_id' => $mary->id,
        ]);

        $dummyRegion = Region::factory()->create();

        [$firstRegion, $secondRegion] = Region::factory()
            ->times(2)
            ->create(['department_id' => $department->id]);

        $this->actingAs($this->admin);

        Livewire::test(Departments::class)
            ->set('deletingName', $department->name)
            ->call('setDeletingDepartment', $department)
            ->call('destroy');

        $this
            ->assertSoftDeleted($department)
            ->assertSoftDeleted($firstRegion)
            ->assertSoftDeleted($secondRegion);

        $this->assertNull($dummyRegion->deleted_at);
    }

    /** @test */
    public function it_should_soft_delete_offices_and_regions_when_delete_a_department()
    {
        $mary       = User::factory()->create(['role' => 'Department Manager']);
        $department = Department::factory()->create([
            'department_manager_id' => $mary->id,
        ]);

        $dummyRegion = Region::factory()->create();
        $dummyOffice = Office::factory()->create();

        [$firstRegion, $secondRegion] = Region::factory()
            ->times(2)
            ->create(['department_id' => $department->id]);

        $officeFromFirstRegion  = Office::factory()->create(['region_id' => $firstRegion->id]);
        $officeFromSecondRegion = Office::factory()->create(['region_id' => $secondRegion->id]);

        $this->actingAs($this->admin);

        Livewire::test(Departments::class)
            ->set('deletingName', $department->name)
            ->call('setDeletingDepartment', $department)
            ->call('destroy');

        $this
            ->assertSoftDeleted($department)
            ->assertSoftDeleted($firstRegion)
            ->assertSoftDeleted($officeFromFirstRegion)
            ->assertSoftDeleted($officeFromSecondRegion)
            ->assertSoftDeleted($secondRegion);

        $this->assertNull($dummyRegion->deleted_at);
        $this->assertNull($dummyOffice->deleted_at);
    }

    /** @test */
    public function it_should_soft_delete_daily_numbers_when_delete_a_department()
    {
        $john       = User::factory()->create(['role' => 'Admin']);
        $mary       = User::factory()->create(['role' => 'Department Manager']);
        $department = Department::factory()->create([
            'department_manager_id' => $mary->id,
        ]);

        [$firstRegion, $secondRegion] = Region::factory()
            ->times(2)
            ->create(['department_id' => $department->id]);

        $officeFromFirstRegion  = Office::factory()->create(['region_id' => $firstRegion->id]);
        $officeFromSecondRegion = Office::factory()->create(['region_id' => $secondRegion->id]);

        $zack = $this->makeUserFromOffice($officeFromFirstRegion);
        $jack = $this->makeUserFromOffice($officeFromSecondRegion);

        $zackDailyNumbers = $this->makeDailyNumberForUser($zack);
        $jackDailyNumbers = $this->makeDailyNumberForUser($jack);

        $this->actingAs($john);

        Livewire::test(Departments::class)
            ->set('deletingName', $department->name)
            ->call('setDeletingDepartment', $department)
            ->call('destroy');

        $this
            ->assertSoftDeleted($department)
            ->assertSoftDeleted($firstRegion)
            ->assertSoftDeleted($officeFromFirstRegion)
            ->assertSoftDeleted($officeFromSecondRegion)
            ->assertSoftDeleted($secondRegion);

        $zackDailyNumbers->each(fn(DailyNumber $dailyNumber) => $this->assertSoftDeleted($dailyNumber));
        $jackDailyNumbers->each(fn(DailyNumber $dailyNumber) => $this->assertSoftDeleted($dailyNumber));
    }

    /** @test */
    public function it_should_soft_delete_all_users_from_the_department_that_is_being_deleted()
    {
        $john       = User::factory()->create(['role' => 'Admin']);
        $mary       = User::factory()->create(['role' => 'Department Manager']);
        $department = Department::factory()->create([
            'department_manager_id' => $mary->id,
        ]);

        /** @var Collection */
        $dummyUsers = User::factory()
            ->times(5)
            ->create(['department_id' => $department->id]);

        $this->actingAs($john);

        Livewire::test(Departments::class)
            ->set('deletingName', $department->name)
            ->call('setDeletingDepartment', $department)
            ->call('destroy');

        $this->assertSoftDeleted($department);

        $dummyUsers->each(fn (User $user) => $this->assertSoftDeleted($user));
    }

    private function makeDailyNumberForUser(User $user, int $times = 2)
    {
        return DailyNumber::factory()
            ->times($times)
            ->create(['user_id' => $user->id]);
    }

    public function makeUserFromOffice(Office $office)
    {
        return User::factory()->create([
            'role'      => 'Setter',
            'office_id' => $office->id,
        ]);
    }
}
