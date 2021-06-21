<?php

namespace Tests\Feature\Castle\Office;

use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetOfficeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_offices()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $regionManager = User::factory()->create([
            'department_id' => $department->id,
            'role'          => 'Region Manager',
        ]);

        $region        = Region::factory()->create([
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
        ]);
        $officeManager = User::factory()->create(['role' => 'Office Manager']);

        $offices = Office::factory()->count(6)->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $response = $this
            ->actingAs($departmentManager)
            ->get(route('castle.offices.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.offices.index');

        foreach ($offices as $office) {
            $response->assertSee($office->name);
        }
    }
}
