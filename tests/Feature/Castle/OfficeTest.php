<?php

namespace Tests\Feature\Castle;

use App\Models\Office;
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
        $offices = factory(Office::class, 6)->create();

        $response = $this->get('/offices');

        $response->assertStatus(200)
            ->assertViewIs('castle.offices')
            ->assertViewHas('offices');

        foreach ($offices as $office) {
            $response->assertSee($office->name);
        }
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['master' => false]));

        $response = $this->get('offices/create');

        $response->assertStatus(403);
    }

     /** @test */
     public function it_should_show_the_create_form_for_top_level_roles()
     {
        $response = $this->get('offices/create');
        
        $response->assertStatus(200)
            ->assertViewIs('castle.offices.create');
     }

    /** @test */
    public function it_should_store_a_new_office()
    {
        $data = [
            'number_installs'   => 48,
            'name'              => 'Office',
            'installs_achieved' => 56,
            'installs_needed'   => 67,
            'kw_achieved'       => 78,
            'kw_needed'         => 100,
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
            'number_installs'   => '',
            'name'              => '',
            'installs_achieved' => '',
            'installs_needed'   => '',
            'kw_achieved'       => '',
            'kw_needed'         => '',
        ];

        $response = $this->post(route('castle.offices.store'), $data);
        $response->assertSessionHasErrors(
        [
            'number_installs',
            'name',
            'installs_achieved',
            'installs_needed',
            'kw_achieved',
            'kw_needed',
        ]);
    }

    /** @test */
    public function it_should_show_the_edit_form_for_top_level_roles()
    {
        $office = factory(Office::class)->create();

        $response = $this->get('offices/'. $office->id . '/edit');
        
        $response->assertStatus(200)
            ->assertViewIs('castle.offices.edit');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Setter']));
        
        $office = factory(Office::class)->create();

        $response = $this->get('offices/'. $office->id .'/edit');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_update_an_office()
    {
        $office       = factory(Office::class)->create(['name' => 'Office']);
        $data            = $office->toArray();
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
        $office = factory(Office::class)->create();

        $response = $this->delete(route('castle.offices.destroy', $office->id));
        $deleted  = Office::where('id', $office->id)->get();

        $response->assertStatus(302);

        $this->assertNotNull($deleted);
    }
}