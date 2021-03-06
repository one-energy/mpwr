<?php

namespace Tests\Feature\Castle\User;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DestroyUserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => Role::ADMIN]);
    }

    /** @test */
    public function it_should_delete_a_user()
    {
        /** @var User $dummy */
        $dummy = User::factory()->create(['role' => Role::SETTER]);

        $this->assertNull($dummy->deleted_at);

        $this->deleteUser($dummy)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertSoftDeleted($dummy);
    }

    /** @test */
    public function it_should_prevent_delete_a_user_if_he_is_managing_some_department()
    {
        /** @var User $dummy */
        $dummy      = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $department = Department::factory()->create(['department_manager_id' => $dummy->id]);

        $dummy->update(['department_id' => $department->id]);

        $this->assertNull($dummy->deleted_at);

        $this->deleteUser($dummy)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertNull($dummy->deleted_at);
    }

    /** @test */
    public function it_should_prevent_delete_a_user_if_he_is_managing_some_region()
    {
        /** @var User $dummy */
        $dummy  = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $region = Region::factory()->create([
            'region_manager_id' => $dummy->id,
            'department_id'     => Department::factory()->create()->id
        ]);

        $dummy->update(['department_id' => $region->department->id]);

        $this->assertNull($dummy->deleted_at);

        $this->deleteUser($dummy)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertNull($dummy->deleted_at);
    }

    /** @test */
    public function it_should_prevent_delete_a_user_if_he_is_managing_some_office()
    {
        /** @var User $dummy */
        $dummy  = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $office = Office::factory()->create([
            'office_manager_id' => $dummy->id,
            'region_id'         => Region::factory([
                'region_manager_id' => User::factory()->create(['role' => Role::REGION_MANAGER])->id,
                'department_id'     => Department::factory()->create()->id
            ]),
        ]);

        $dummy->update(['department_id' => $office->region->department->id]);

        $this->assertNull($dummy->deleted_at);

        $this->deleteUser($dummy)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertNull($dummy->deleted_at);
    }

    /** @test */
    public function it_should_prevent_the_authenticated_user_delete_it_self()
    {
        $this->deleteUser($this->admin)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('users', [
            'email' => $this->admin->email,
            'id'    => $this->admin->id
        ]);
    }

    private function deleteUser(User $admin)
    {
        return $this->actingAs($this->admin)
            ->delete(route('castle.users.destroy', ['user' => $admin->id]));
    }
}
