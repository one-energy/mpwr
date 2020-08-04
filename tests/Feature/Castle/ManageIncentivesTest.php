<?php

namespace Tests\Feature\Castle;

use App\Models\Incentive;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class incentiveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['role' => 'Admin']);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_incentives()
    {
        $incentives = factory(Incentive::class, 6)->create();

        $response = $this->get('/castle/settings/incentives');

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
        $this->actingAs(factory(User::class)->create(['role' => 'Setter']));

        $response = $this->get('castle/settings/incentives/create');

        $response->assertStatus(403);
    }

     /** @test */
     public function it_should_show_the_create_form_for_top_level_roles()
     {
        $response = $this->get('castle/settings/incentives/create');
        
        $response->assertStatus(200)
            ->assertViewIs('castle.incentives.create');
     }

    /** @test */
    public function it_should_store_a_new_incentive()
    {
        $data = [
            'number_installs'   => 48,
            'name'              => 'Incentive',
            'installs_achevied' => 56,
            'installs_needed'   => 67,
            'kw_achieved'       => 78,
            'kw_needed'         => 100,
        ];

        $response = $this->post(route('castle.settings.incentives.store'), $data);

        $created = Incentive::where('name', $data['name'])->first();

        $response->assertStatus(302)
            ->assertSessionHas('message', 'Incentive created!')
            ->assertRedirect(route('settings.incentives.edt', $created->id));
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_incentive()
    {
        $data = [
            'number_installs'   => '',
            'name'              => '',
            'installs_achevied' => '',
            'installs_needed'   => '',
            'kw_achieved'       => '',
            'kw_needed'         => '',
        ];

        $response = $this->post(route('castle.settings.incentives..store'), $data);
        $response->assertSessionHasErrors(
        [
            'number_installs',
            'name',
            'installs_achevied',
            'installs_needed',
            'kw_achieved',
            'kw_needed',
        ]);
    }

    /** @test */
    public function it_should_show_the_edit_form_for_top_level_roles()
    {
        $incentive = factory(Incentive::class)->create();

        $response = $this->get('castle/settings/incentives/'. $incentive->id);
        
        $response->assertStatus(200)
            ->assertViewIs('castle.incentives.show');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Setter']));
        
        $incentive = factory(Incentive::class)->create();

        $response = $this->get('castle/settings/incentives/'. $incentive->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_update_a_incentive()
    {
        $incentive       = factory(Incentive::class)->create(['name' => 'Incentive']);
        $data            = $incentive->toArray();
        $updateincentive = array_merge($data, ['name' => 'Incentive Edited']);

        $response = $this->put(route('castle.settings.incentives.update', $incentive->id), $updateincentive);
            
        $response->assertStatus(302)
            ->assertSessionHas('message', 'Incentive updated!');

        $this->assertDatabaseHas('incentives',
        [
            'id'   => $incentive->id,
            'name' => 'Incentive Edited'
        ]);
    }

    /** @test */
    public function it_should_destroy_a_incentive()
    {
        $incentive = factory(Incentive::class)->create();

        $response = $this->delete(route('castle.settings.incentives.destroy', $incentive->id), $incentive);

        $response->assertStatus(302)
            ->assertSessionHas('message', 'Incentive deleted!');

        $this->assertDatabaseMissing('incentives', [
            'id' => $incentive->id,
        ]);
    }
}