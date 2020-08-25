<?php

namespace Tests\Feature\Castle;

use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfficeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_offices()
    {
        $region        = factory(Region::class)->create(['owner_id' => $this->user->id]);
        $officeManager = factory(User::class)->create(['role' => 'Office Manager']);
        $offices       = factory(Office::class, 6)->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $response = $this->get('castle/offices');

        $response->assertStatus(200)
            ->assertViewIs('castle.offices.index')
            ->assertViewHas('offices');

        foreach ($offices as $office) {
            $response->assertSee($office->name);
        }
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['master' => false]));

        $response = $this->get('castle/offices/create');

        $response->assertStatus(403);
    }

     /** @test */
     public function it_should_show_the_create_form_for_top_level_roles()
     {
        $response = $this->get('castle/offices/create');
        
        $response->assertStatus(200)
            ->assertViewIs('castle.offices.create');
     }

    /** @test */
    public function it_should_store_a_new_office()
    {
        $region        = factory(Region::class)->create(['owner_id' => $this->user->id]);
        $officeManager = factory(User::class)->create(['role' => 'Office Manager']);

        $data = [
            'name'              => 'Office',
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ];

        $response = $this->post(route('castle.offices.store'), $data);

        $created = Office::where('name', $data['name'])->first();

        $response->assertStatus(302)
            ->assertRedirect(route('castle.offices.edit', $created));
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_office()
    {
        $data = [
            'name'              => '',
            'region_id'         => '',
            'office_manager_id' => '',
        ];

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
        $region        = factory(Region::class)->create(['owner_id' => $this->user->id]);
        $officeManager = factory(User::class)->create(['role' => 'Office Manager']);
        $office        = factory(Office::class)->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $response = $this->get('castle/offices/'. $office->id . '/edit');
        
        $response->assertStatus(200)
            ->assertViewIs('castle.offices.edit');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Setter']));
        
        $region        = factory(Region::class)->create(['owner_id' => $this->user->id]);
        $officeManager = factory(User::class)->create(['role' => 'Office Manager']);
        $office        = factory(Office::class)->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $response = $this->get('castle/offices/'. $office->id .'/edit');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_update_an_office()
    {
        $region        = factory(Region::class)->create(['owner_id' => $this->user->id]);
        $officeManager = factory(User::class)->create(['role' => 'Office Manager']);
        $office        = factory(Office::class)->create([
            'name'              => 'Office',
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);
        $data         = $office->toArray();
        $updateOffice = array_merge($data, ['name' => 'Office Edited']);

        $response = $this->put(route('castle.offices.update', $office->id), $updateOffice);
            
        $response->assertStatus(302);

        $this->assertDatabaseHas('offices',
        [
            'id'   => $office->id,
            'name' => 'Office Edited'
        ]);
    }

    /** @test */
    public function it_should_destroy_an_office()
    {
        $region        = factory(Region::class)->create(['owner_id' => $this->user->id]);
        $officeManager = factory(User::class)->create(['role' => 'Office Manager']);
        $office        = factory(Office::class)->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $response = $this->delete(route('castle.offices.destroy', $office->id));
        $deleted  = Office::where('id', $office->id)->get();

        $response->assertStatus(302);

        $this->assertNotNull($deleted);
    }
}