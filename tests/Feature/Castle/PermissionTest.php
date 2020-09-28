<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'role' => 'Admin', 
            'master' => true
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_show_the_edit_form()
    {
        $user = factory(User::class)->create();

        $response = $this->get('castle/permission/'. $user->id .'/edit');
        
        $response->assertStatus(200)
            ->assertViewIs('castle.permission.edit');
    }

    /** @test */
    public function it_should_update_an_users_role()
    {
        $user       = factory(User::class)->create(['role' => 'Setter']);
        $data       = $user->toArray();
        $updateUser = array_merge($data, ['role' => 'Sales Rep']);

        $response = $this->put(route('castle.permission.update', $user->id), $updateUser);
            
        $response->assertStatus(302);

        $this->assertDatabaseHas('users',
        [
            'id'   => $user->id,
            'role' => 'Sales Rep'
        ]);
    }
}