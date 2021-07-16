<?php

namespace Tests\Feature\Castle\User;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\OfficeBuilder;
use Tests\Builders\RegionBuilder;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_show_the_office_a_user_is_on()
    {
        $master  = UserBuilder::build(['role' => Role::ADMIN])->asMaster()->save()->get();
        $region  = RegionBuilder::build()->withManager($master)->save()->get();
        $office1 = OfficeBuilder::build()->region($region)->withManager($master)->save()->get();
        $user1   = UserBuilder::build(['role' => Role::SETTER])->withOffice($office1)->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.show', $user1->id))
            ->assertViewIs('castle.users.show')
            ->assertSee($user1->first_name);
    }

    /** @test */
    public function it_should_show_resend_invitation_email_button()
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $user = User::factory()->create(['email_verified_at' => null]);

        $this
            ->actingAs($admin)
            ->get(route('castle.users.show', $user))
            ->assertSee('Resend invitation email');
    }

    /** @test */
    public function it_shouldt_show_resend_invitation_email_for_non_admin_users()
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($departmentManager)
            ->get(route('castle.users.show', $user))
            ->assertDontSee('Resend invitation email');
    }

    /** @test */
    public function it_should_show_success_alert_when_resend_invitation_email()
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $user  = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($admin)
            ->post(route('verification.resendInvitationEmail', $user))
            ->assertSee('Success');
    }
}
