<?php

namespace Tests\Feature\Castle\Office;

use App\Enum\Role;
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
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $officeManager     = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($departmentManager->id);
        $departmentManager->update(['department_id' => $department->id]);

        /** @var Region $region */
        $region = Region::factory()->create(['department_id' => $department->id]);
        $region->managers()->attach($this->user->id);

        /** @var Office $office */
        $office = Office::factory()->create(['region_id' => $region->id]);
        $office->managers()->attach($officeManager->id);

        $this->actingAs($departmentManager)
            ->get(route('castle.offices.edit', ['office' => $office]))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.offices.edit');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {
        $officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($this->user->id);

        /** @var Office $office */
        $office = Office::factory()->create(['region_id' => $region->id]);
        $office->managers()->attach($officeManager->id);

        $this
            ->actingAs(User::factory()->create(['role' => Role::SETTER]))
            ->get(route('castle.offices.edit', ['office' => $office]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_update_an_office()
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $officeManager     = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($departmentManager->id);
        $departmentManager->update(['department_id' => $department->id]);

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($this->user->id);

        /** @var Office $office */
        $office = Office::factory()->create([
            'name'      => 'Office',
            'region_id' => $region->id,
        ]);
        $office->managers()->attach($officeManager->id);

        $data         = $office->toArray();
        $updateOffice = array_merge($data, ['name' => 'Office Edited']);

        $this->actingAs($departmentManager)
            ->put(route('castle.offices.update', $office->id), $updateOffice)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('offices', [
            'id'   => $office->id,
            'name' => 'Office Edited',
        ]);
    }
}
