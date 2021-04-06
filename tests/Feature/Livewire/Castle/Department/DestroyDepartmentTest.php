<?php

namespace Tests\Feature\Livewire\Castle\Department;

use App\Http\Livewire\Castle\Departments;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DestroyDepartmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_soft_delete_a_department()
    {
        $john       = User::factory()->create(['role' => 'Admin']);
        $mary       = User::factory()->create(['role' => 'Department Manager']);
        $department = Department::factory()->create([
            'department_manager_id' => $mary->id,
        ]);

        $this->actingAs($john);

        Livewire::test(Departments::class)
            ->call('setDeletingDepartment', $department)
            ->call('destroy');

        $this->assertSoftDeleted($department);
    }

    /** @test */
    public function it_should_soft_delete_regions_when_delete_a_department()
    {
        $john       = User::factory()->create(['role' => 'Admin']);
        $mary       = User::factory()->create(['role' => 'Department Manager']);
        $department = Department::factory()->create([
            'department_manager_id' => $mary->id,
        ]);

        $dummyRegion = Region::factory()->create();

        [$firstRegion, $secondRegion] = Region::factory()
            ->times(2)
            ->create([
                'department_id' => $department->id,
            ]);

        $this->actingAs($john);

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
        $john       = User::factory()->create(['role' => 'Admin']);
        $mary       = User::factory()->create(['role' => 'Department Manager']);
        $department = Department::factory()->create([
            'department_manager_id' => $mary->id,
        ]);

        $dummyRegion = Region::factory()->create();
        $dummyOffice = Office::factory()->create();

        [$firstRegion, $secondRegion] = Region::factory()
            ->times(2)
            ->create([
                'department_id' => $department->id,
            ]);

        $officeFromFirstRegion  = Office::factory()->create(['region_id' => $firstRegion->id]);
        $officeFromSecondRegion = Office::factory()->create(['region_id' => $secondRegion->id]);

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

        $this->assertNull($dummyRegion->deleted_at);
        $this->assertNull($dummyOffice->deleted_at);
    }
}
