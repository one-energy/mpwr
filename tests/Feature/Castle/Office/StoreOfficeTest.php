<?php

namespace Tests\Feature\Castle\Office;

use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class StoreOfficeTest extends TestCase
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
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $setter = User::factory()->create([
            'role' => 'Setter',
        ]);

        $department = Department::factory()->create([
            'department_manager_id' => $setter->id,
        ]);

        $setter->department_id = $department->id;
        $setter->save();

        $this->actingAs($setter)
            ->get(route('castle.offices.create'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this
            ->actingAs($departmentManager)
            ->get(route('castle.offices.create'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.offices.create');
    }

    /** @test */
    public function it_should_store_a_new_office()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $region        = Region::factory()->create(['region_manager_id' => $this->user->id]);
        $officeManager = User::factory()->create(['role' => 'Office Manager']);

        $data = [
            'name'              => 'Office',
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ];

        $response = $this->actingAs($departmentManager)
            ->post(route('castle.offices.store'), $data)
            ->assertStatus(Response::HTTP_FOUND);

        $created = Office::where('name', $data['name'])->first();

        $response->assertRedirect(route('castle.offices.index', $created));
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_office()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs($departmentManager)
            ->post(route('castle.offices.store'), [])
            ->assertSessionHasErrors([
                'name',
                'region_id',
                'office_manager_id',
            ]);
    }
}
