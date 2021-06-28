<?php

namespace Tests\Feature\Castle\User;

use App\Enum\Role;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\UserInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role'  => Role::ADMIN,
            'email' => 'devsquad@mail.com'
        ]);
    }

    /** @test */
    public function it_should_store_a_user()
    {
        Notification::fake();

        $dummy = $this->makeUser([
            'role'          => Role::DEPARTMENT_MANAGER,
            'department_id' => null,
            'office_id'     => null
        ]);

        $this->createUser($dummy);

        $this->assertDatabaseHas('users', ['email' => $dummy->email]);
    }

    /** @test */
    public function it_should_dispatch_a_notification_after_store_the_user()
    {
        Notification::fake();

        $dummy = $this->makeUser([
            'email'         => 'sample@mail.com',
            'role'          => Role::DEPARTMENT_MANAGER,
            'department_id' => null,
            'office_id'     => null
        ]);

        $this->createUser($dummy);

        $this->assertDatabaseHas('invitations', ['email' => $dummy->email]);

        /** @var User $createdUser */
        $createdUser = User::where('email', $dummy->email)->first();

        /** @var Invitation $invitation */
        $invitation = Invitation::where('email', $createdUser->email)->first();

        $invitation->notify(new UserInvitation());

        Notification::assertSentTo($invitation, UserInvitation::class);
    }

    /** @test */
    public function it_should_set_office_id_to_null_if_the_provided_value_is_none()
    {
        Notification::fake();

        $dummy = $this->makeUser([
            'role'          => Role::DEPARTMENT_MANAGER,
            'department_id' => null,
            'office_id'     => 'None'
        ]);

        $this->createUser($dummy);

        $dummy->refresh();

        $this->assertDatabaseHas('users', [
            'email'     => $dummy->email,
            'office_id' => null
        ]);
    }

    /** @test */
    public function it_should_set_department_id_to_null_if_the_provided_value_is_none()
    {
        Notification::fake();

        $dummy = $this->makeUser([
            'role'          => Role::DEPARTMENT_MANAGER,
            'department_id' => 'None',
            'office_id'     => null
        ]);

        $this->createUser($dummy);

        $dummy->refresh();

        $this->assertDatabaseHas('users', [
            'email'         => $dummy->email,
            'department_id' => null
        ]);
    }

    /** @test */
    public function it_should_set_department_id_to_null_if_the_provided_role_is_admin_or_owner()
    {
        Notification::fake();

        $dummy = $this->makeUser([
            'role'          => Role::ADMIN,
            'department_id' => 1,
            'office_id'     => null
        ]);

        $this->createUser($dummy);

        $dummy->refresh();

        $this->assertDatabaseHas('users', [
            'email'         => $dummy->email,
            'department_id' => null
        ]);
    }

    /** @test */
    public function it_should_require_first_name()
    {
        $this->createUser($this->makeUser(['first_name' => null]))
            ->assertSessionHasErrors(['first_name']);
    }

    /** @test */
    public function it_should_require_last_name_name()
    {
        $this->createUser($this->makeUser(['last_name' => null]))
            ->assertSessionHasErrors(['last_name']);
    }

    /** @test */
    public function it_should_require_email()
    {
        $this->createUser($this->makeUser(['email' => null]))
            ->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function it_should_require_a_valid_role()
    {
        $this->createUser($this->makeUser(['role' => Str::random()]))
            ->assertSessionHasErrors(['role']);
    }

    /** @test */
    public function it_should_prevent_first_name_greater_than_255_characters()
    {
        $this->createUser($this->makeUser(['first_name' => Str::random(256)]))
            ->assertSessionHasErrors(['first_name']);
    }

    /** @test */
    public function it_should_prevent_last_name_greater_than_255_characters()
    {
        $this->createUser($this->makeUser(['last_name' => Str::random(256)]))
            ->assertSessionHasErrors(['last_name']);
    }

    /** @test */
    public function it_should_require_a_valid_email()
    {
        $this->createUser($this->makeUser(['email' => 'devsquad@.com']))
            ->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function it_should_prevent_users_with_duplicated_emails()
    {
        $this->createUser($this->makeUser(['email' => 'devsquad@mail.com']))
            ->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function it_should_prevent_invitation_with_duplicated_emails()
    {
        Invitation::factory()->create(['email' => 'unique@mailcom']);

        $this->createUser($this->makeUser(['email' => 'unique@mailcom']))
            ->assertSessionHasErrors(['email']);
    }

    private function makeUser(array $attributes = []): User
    {
        return User::factory()->make($attributes);
    }

    private function createUser(User $user)
    {
        return $this
            ->actingAs($this->admin)
            ->post(route('castle.users.store'), $user->toArray());
    }
}
