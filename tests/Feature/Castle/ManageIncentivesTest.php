<?php

namespace Tests\Feature\Castle;

use App\Models\Incentive;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageIncentiveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_incentives()
    {
        $incentives = factory(Incentive::class, 6)->create();

        $response = $this->get('castle/incentives');

        $response->assertStatus(200)
            ->assertViewIs('castle.incentives.index')
            ->assertViewHas('incentives');

        foreach ($incentives as $incentive) {
            $response->assertSee($incentive->name);
        }
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['master' => false]));

        $response = $this->get('/castle/incentives/create');

        $response->assertStatus(403);
    }

     /** @test */
     public function it_should_show_the_create_form_for_top_level_roles()
     {
        $response = $this->get('castle/incentives/create');
        
        $response->assertStatus(200)
            ->assertViewIs('castle.incentives.create');
     }

    /** @test */
    public function it_should_store_a_new_incentive()
    {
        $data = [
            'number_installs'   => 48,
            'name'              => 'Incentive',
            'installs_achieved' => 56,
            'installs_needed'   => 67,
            'kw_achieved'       => 78,
            'kw_needed'         => 100,
        ];

        $response = $this->post(route('castle.incentives.store'), $data);

        $created = Incentive::where('name', $data['name'])->first();

        $response->assertStatus(302)
            ->assertRedirect(route('castle.incentives.edit', $created));
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_incentive()
    {
        $data = [
            'number_installs'   => '',
            'name'              => '',
            'installs_achieved' => '',
            'installs_needed'   => '',
            'kw_achieved'       => '',
            'kw_needed'         => '',
        ];

        $response = $this->post(route('castle.incentives.store'), $data);
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
        $incentive = factory(Incentive::class)->create();

        $response = $this->get('castle/incentives/'. $incentive->id . '/edit');
        
        $response->assertStatus(200)
            ->assertViewIs('castle.incentives.edit');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Setter']));
        
        $incentive = factory(Incentive::class)->create();

        $response = $this->get('castle/incentives/'. $incentive->id .'/edit');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_update_an_incentive()
    {
        $incentive       = factory(Incentive::class)->create(['name' => 'Incentive']);
        $data            = $incentive->toArray();
        $updateIncentive = array_merge($data, ['name' => 'Incentive Edited']);

        $response = $this->put(route('castle.incentives.update', $incentive->id), $updateIncentive);
            
        $response->assertStatus(302);

        $this->assertDatabaseHas('incentives',
        [
            'id'   => $incentive->id,
            'name' => 'Incentive Edited'
        ]);
    }

    /** @test */
    public function it_should_destroy_an_incentive()
    {
        $incentive = factory(Incentive::class)->create();

        $response = $this->delete(route('castle.incentives.destroy', $incentive->id));
        $deleted  = Incentive::where('id', $incentive->id)->get();

        $response->assertStatus(302);

        $this->assertNotNull($deleted);
    }
}