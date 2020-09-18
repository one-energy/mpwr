<?php

namespace Tests\Feature\Castle;

use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_store_a_new_region()
    {
        $regionManager = factory(User::class)->create(['role' => 'Region Manager']);

        $data = [
            'name'              => 'Region',
            'region_manager_id' => $regionManager->id,
        ];

        $response = $this->post(route('castle.regions.store'), $data);

        $created = Region::where('name', $data['name'])->first();

        $response->assertStatus(302)
            ->assertRedirect(route('castle.regions.edit', $created));
    }

    /** @test */
    public function it_should_list_all_regions()
    {
        $regionManager = factory(User::class)->create(['role' => 'Region Manager']);
        $regions       = factory(Region::class, 6)->create([
            'region_manager_id' => $regionManager->id,
        ]);

        $response = $this->get('castle/regions');

        $response->assertStatus(200)
            ->assertViewIs('castle.regions.index')
            ->assertViewHas('regions');

        foreach ($regions as $region) {
            $response->assertSee($region->name);
        }
    }

    /** @test */
    public function it_should_edit_an_region()
    {
        $regionManager = factory(User::class)->create(['role' => 'Region Manager']);
        $region        = factory(Region::class)->create([
            'name'              => 'New Region',
            'region_manager_id' => $regionManager->id,
        ]);
        $data         = $region->toArray();
        $data['region_manager_id'] = $data['region_manager_id'];
        $updateRegion = array_merge($data, ['name' => 'Region Edited']);

        $response = $this->put(route('castle.regions.update', $region->id), $updateRegion);

        $response->assertStatus(302);

        $this->assertDatabaseHas(
            'Regions',
            [
                'id'   => $region->id,
                'name' => 'Region Edited'
            ]
        );
    }

    /** @test */
    public function it_should_destroy_an_region()
    {
        $regionManager = factory(User::class)->create(['role' => 'Region Manager']);
        $region        = factory(Region::class)->create([
            'region_manager_id' => $regionManager->id,
        ]);

        $response = $this->delete(route('castle.regions.destroy', $region->id));
        $deleted  = Region::where('id', $region->id)->get();

        $response->assertStatus(302);

        $this->assertNotNull($deleted);
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['master' => false]));

        $response = $this->get('castle/regions/create');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
       $response = $this->get('castle/regions/create');
       
       $response->assertStatus(200)
           ->assertViewIs('castle.regions.create');
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_region()
    {
        $data = [
            'name'              => '',
            'region_manager_id' => '',
        ];

        $response = $this->post(route('castle.regions.store'), $data);
        $response->assertSessionHasErrors(
        [
            'name',
            'region_manager_id',
        ]);
    }
}   