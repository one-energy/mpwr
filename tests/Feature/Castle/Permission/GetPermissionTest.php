<?php

namespace Tests\Feature\Castle\Permission;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role'   => 'Admin',
            'master' => true,
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_show_the_edit_form()
    {
        $user = User::factory()->create();

        $this->get(route('castle.permission.edit', ['user' => $user]))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.permission.edit');
    }

    /** @test */
    public function it_should_update_an_users_role()
    {
        $user = User::factory()->create(['role' => 'Setter']);
        $data = $user->toArray();

        $this->put(route('castle.permission.update', $user->id), array_merge($data, ['role' => 'Sales Rep']))
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'role' => 'Sales Rep',
        ]);
    }
}
