<?php

namespace Tests\Feature\Castle\UserDetails;

use App\Enum\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Builders\OfficeBuilder;
use Tests\Builders\RegionBuilder;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class GetUserDetailsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'Admin']);

        $this->actingAs($this->user);
    }

    /** @test */
    public function only_master_users_can_see_user_details()
    {
        $departmentManager = User::factory()->create([
            'role' => 'Department Manager',
        ]);

        $department = Department::factory()->create([
            'department_manager_id' => $departmentManager->id,
        ]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

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
            'role' => 'Admin',
        ]);

        $nonMaster = User::factory()->create([
            'role'          => 'Department Manager',
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
        $master = (new UserBuilder(['role' => 'Admin']))->asMaster()->save()->get();

        $region = (new RegionBuilder)->withManager($master)->save()->get();

        $office1 = (new OfficeBuilder)->region($region)->withManager($master)->save()->get();

        $user1 = (new UserBuilder)->withOffice($office1)->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.show', $user1->id))
            ->assertViewIs('castle.users.show')
            ->assertSee($user1->first_name);
    }

    /** @test */
    public function it_should_reset_users_password()
    {
        $master      = User::factory()->create(['role' => 'admin']);
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
        $setterManager = User::factory()->create(['role' => 'Setter']);

        $this->actingAs($setterManager)
            ->get(route('castle.users.index'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_show_resend_invitation_email_button()
    {
        $user = User::factory()->create([
            "email_verified_at" => null
        ]);

        $this->get(route('castle.users.show', $user))
            ->assertSee('Resend invitation email');
    }

    /** @test */
    public function it_shouldt_show_resend_invitation_email_for_non_admin_users()
    {
        $departmentManager = User::factory()->create([
            'role' => Role::DEPARTMENT_MANAGER
        ]);

        $user = User::factory()->create([
            "email_verified_at" => null
        ]);

        $this->actingAs($departmentManager)
            ->get(route('castle.users.show', $user))
            ->assertDontSee('Resend invitation email');
    }

    /** @test */
    public function it_should_show_success_alert_when_resend_invitation_email()
    {
        $admin = User::factory()->create([
            'role' => Role::ADMIN
        ]);

        $user = User::factory()->create([
            "email_verified_at" => null
        ]);

        $this->actingAs($admin)
            ->post(route('verification.resendInvitationEmail', $user))
            ->assertSee('Success');
    }
}
