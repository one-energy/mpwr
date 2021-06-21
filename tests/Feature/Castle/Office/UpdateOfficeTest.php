<?php

namespace Tests\Feature\Castle\Office;

use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateOfficeTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_show_the_edit_form_for_top_level_roles()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $region        = Region::factory()->create([
            'region_manager_id' => $this->user->id,
            'department_id'     => $department->id,
        ]);
        $officeManager = User::factory()->create(['role' => 'Office Manager']);
        $office        = Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $this->actingAs($departmentManager)
            ->get(route('castle.offices.edit', ['office' => $office]))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.offices.edit');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {
        $this->actingAs(User::factory()->create(['role' => 'Setter']));

        $region        = Region::factory()->create(['region_manager_id' => $this->user->id]);
        $officeManager = User::factory()->create(['role' => 'Office Manager']);
        $office        = Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $this->get(route('castle.offices.edit', ['office' => $office]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_update_an_office()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $region        = Region::factory()->create(['region_manager_id' => $this->user->id]);
        $officeManager = User::factory()->create(['role' => 'Office Manager']);
        $office        = Office::factory()->create([
            'name'              => 'Office',
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);
        $data          = $office->toArray();
        $updateOffice  = array_merge($data, ['name' => 'Office Edited']);

        $this->actingAs($departmentManager)
            ->put(route('castle.offices.update', $office->id), $updateOffice)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('offices', [
            'id'   => $office->id,
            'name' => 'Office Edited',
        ]);
    }
}
