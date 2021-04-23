<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfficeTest extends TestCase
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
            'region_manager_id' => $regionManager->id ,
            'department_id'     => $department->id,
        ]);
        $officeManager = User::factory()->create(['role' => 'Office Manager']);

        $offices       = Office::factory()->count(6)->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $this->actingAs($departmentManager);

        $response = $this->get('castle/offices');

        $response->assertStatus(200)
            ->assertViewIs('castle.offices.index');

        foreach ($offices as $office) {
            $response->assertSee($office->name);
        }
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

        $this->actingAs($setter);

        $response = $this->get('castle/offices/create');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs($departmentManager);

        $response = $this->get('castle/offices/create');
        $response->assertStatus(200)
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

        $this->actingAs($departmentManager);

        $response = $this->post(route('castle.offices.store'), $data);

        $created = Office::where('name', $data['name'])->first();

        $response->assertStatus(302)
            ->assertRedirect(route('castle.offices.index', $created));
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_office()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $data = [
            'name'              => '',
            'region_id'         => '',
            'office_manager_id' => '',
            'department_id'     => '',
        ];

        $this->actingAs($departmentManager);

        $response = $this->post(route('castle.offices.store'), $data);
        $response->assertSessionHasErrors(
        [
            'name',
            'region_id',
            'office_manager_id',
        ]);
    }

    /** @test */
    public function it_should_show_the_edit_form_for_top_level_roles()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $region        = Region::factory()->create([
            'region_manager_id'  => $this->user->id,
            'department_id'      => $department->id,
        ]);
        $officeManager = User::factory()->create(['role' => 'Office Manager']);
        $office        = Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $this->actingAs($departmentManager);

        $response = $this->get('castle/offices/' . $office->id . '/edit');

        $response->assertStatus(200)
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

        $response = $this->get('castle/offices/' . $office->id . '/edit');

        $response->assertStatus(403);
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
        $data         = $office->toArray();
        $updateOffice = array_merge($data, ['name' => 'Office Edited']);

        $this->actingAs($departmentManager);

        $response = $this->put(route('castle.offices.update', $office->id), $updateOffice);

        $response->assertStatus(302);

        $this->assertDatabaseHas('offices',
        [
            'id'   => $office->id,
            'name' => 'Office Edited',
        ]);
    }

    /** @test */
    public function it_should_destroy_an_office()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $region        = Region::factory()->create(['region_manager_id' => $this->user->id]);
        $officeManager = User::factory()->create(['role' => 'Office Manager']);
        $office        = Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $this->actingAs($departmentManager);

        $response = $this->delete(route('castle.offices.destroy', $office->id));
        $deleted  = Office::where('id', $office->id)->get();

        $response->assertStatus(302);

        $this->assertNotNull($deleted);
    }
}
