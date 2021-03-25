<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_list_all_rates()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();


        $rates       = Rates::factory()->count(6)->create([
            'department_id' => $department->id,
            'role'          => 'Sales Rep',
        ]);

        $this->actingAs($departmentManager);

        $response = $this->get('castle/rates');

        $response->assertStatus(200)
            ->assertViewIs('castle.rate.index');

        foreach ($rates as $rate) {
            $response->assertSee($rate->name);
        }
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles_in_rates()
    {
        $setter = User::factory()->create([
            "role" => "Setter"
        ]);

        $department = Department::factory()->create([
            "department_manager_id" => $setter->id
        ]);

        $setter->department_id = $department->id;
        $setter->save();

        $this->actingAs($setter);

        $response = $this->get('castle/rates/create');

        $response->assertStatus(403);
    }

     /** @test */
     public function it_should_show_the_create_form_for_top_level_roles_in_rates()
     {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs($departmentManager);

        $response = $this->get('castle/rates/create');
        $response->assertStatus(200)
            ->assertViewIs('castle.rate.create');
     }

    /** @test */
    public function it_should_store_a_new_rate()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $data = [
            'name'          => 'rate',
            'time'          => 25,
            'rate'          => 2.5,
            'department_id' => $department->id,
            'role'          => 'Sales Rep',
        ];

        $this->actingAs($departmentManager);

        $response = $this->post(route('castle.rates.store'), $data);

        $created = Rates::where('name', $data['name'])->first();

        $response->assertStatus(302)
            ->assertRedirect(route('castle.rates.index'));
    }

      /** @test */
      public function it_shouldnt_store_a_repeated_rate()
      {
          $departmentManager = User::factory()->create(["role" => "Department Manager"]);
          $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
          $departmentManager->department_id = $department->id;
          $departmentManager->save();

          $data = [
              'name'          => 'rate',
              'time'          => 25,
              'rate'          => 2.5,
              'department_id' => $department->id,
              'role'          => 'Sales Rep',
          ];

          Rates::factory()->create($data);

          $this->actingAs($departmentManager);

          $this->post(route('castle.rates.store'), $data);

          $this->assertDatabaseCount('rates', 1);;
      }


    /** @test */
    public function it_should_require_all_fields_to_store_a_new_rate()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $data = [
            'name'          => '',
            'time'          => '',
            'rate'          => '',
            'department_id' => '',
            'role'          => 'Sales Rep',
        ];

        $this->actingAs($departmentManager);

        $response = $this->post(route('castle.rates.store'), $data);
        $response->assertSessionHasErrors(
        [
            'name',
            'time',
            'rate',
            'department_id',
        ]);
    }

    /** @test */
    public function it_should_show_the_edit_form_for_top_level_roles()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $rate        = Rates::factory()->create([
            'department_id'   => $department->id,
            'role' => 'Sales Rep'
        ]);

        $this->actingAs($departmentManager);

        $response = $this->get('castle/rates/'. $rate->id . '/edit');

        $response->assertStatus(200)
            ->assertViewIs('castle.rate.edit');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs(User::factory()->create(['role' => 'Setter']));

        $rate        = Rates::factory()->create([
            'department_id'         => $department->id,
            'role' => 'Sales Rep'
        ]);

        $response = $this->get('castle/rates/'. $rate->id .'/edit');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_update_an_rate()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $rate        = Rates::factory()->create([
            'department_id' => $department->id,
            'rate'          => 2.1,
            'role' => 'Sales Rep'
        ]);
        $data         = $rate->toArray();
        $updaterate = array_merge($data, ['name' => 'rate Edited']);

        $this->actingAs($departmentManager);

        $response = $this->put(route('castle.rates.update', $rate->id), $updaterate);

        $response->assertStatus(302);
        $this->assertDatabaseHas('rates',
        [
            'id'   => $rate->id,
            'name' => 'rate Edited'
        ]);
    }

    /** @test */
    public function it_should_destroy_an_rate()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $salesRep = User::factory()->create(["role" => "Sales Rep"]);
        $setter = User::factory()->create(["role" => "Setter"]);
        $department        = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $rate        = Rates::factory()->create([
            'department_id' => $department->id,
            'role' => 'Sales Rep'
        ]);

        $this->actingAs($departmentManager);

        $response = $this->delete(route('castle.rates.destroy', $rate->id));
        $deleted  = Rates::where('id', $rate->id)->get();

        $response->assertStatus(302);

        $this->assertNotNull($deleted);
    }
}
