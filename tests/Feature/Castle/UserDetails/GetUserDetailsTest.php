<?php

namespace Tests\Feature\Castle\UserDetails;

use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\Enum\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class GetUserDetailsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function only_master_users_can_see_user_details()
    {
        [$departmentManager, $department] = $this->createVP();

        $setter = User::factory()->create([
            'role'          => 'Setter',
            'department_id' => $department->id,
        ]);

        $this->actingAs($setter)
            ->get(route('castle.users.edit', $setter->id))
            ->assertForbidden();

        $this->actingAs($departmentManager)
            ->get(route('castle.users.edit', $departmentManager->id))
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_show_the_details_for_a_user()
    {
        $department = Department::factory()->create([
            'name' => 'Department One',
        ]);

        $master = User::factory()->create([
            'role' => Role::ADMIN,
        ]);

        $nonMaster = User::factory()->create([
            'role'          => Role::DEPARTMENT_MANAGER,
            'department_id' => $department->id,
        ]);

        $this->actingAs($master)
            ->get(route('castle.users.edit', $master->id))
            ->assertSuccessful()
            ->assertSee($master->first_name)
            ->assertSee($master->last_name)
            ->assertSee($master->email);

        $this->actingAs($master)
            ->get(route('castle.users.edit', $nonMaster->id))
            ->assertSuccessful()
            ->assertSee($nonMaster->first_name)
            ->assertSee($nonMaster->last_name)
            ->assertSee($nonMaster->email);
    }

    /** @test */
    public function it_should_show_the_office_a_user_is_on()
    {
        $this->withoutExceptionHandling();

        $master = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($master->id);

        /** @var Office $office */
        $office = Office::factory()->create(['region_id' => $region->id]);
        $office->managers()->attach($master->id);

        $master->update(['office_id' => $office->id]);

        $user1 = (new UserBuilder(['role' => Role::SETTER]))->withOffice($office)->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.show', $user1->id))
            ->assertViewIs('castle.users.show')
            ->assertSee($user1->first_name);
    }

    /** @test */
    public function it_should_reset_users_password()
    {
        $master      = User::factory()->create(['role' => Role::ADMIN]);
        $user        = User::factory()->create(['password' => '123456789']);
        $data        = $user->toArray();
        $newPassword = array_merge($data, [
            'new_password'              => '123456789',
            'new_password_confirmation' => '123456789',
        ]);

        $this->actingAs($master)
            ->put(route('castle.users.reset-password', $user->id), $newPassword)
            ->assertStatus(Response::HTTP_FOUND);
    }

    /** @test */
    public function it_shouldnt_show_index_page()
    {
        $setterManager = User::factory()->create(['role' => Role::SETTER]);

        $this->actingAs($setterManager)
            ->get(route('castle.users.index'))
            ->assertForbidden();
    }
}
