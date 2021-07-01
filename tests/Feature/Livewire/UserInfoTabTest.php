<?php

namespace Tests\Feature\Livewire;

use App\Enum\Role;
use App\Http\Livewire\Castle\Users\UserInfoTab;
use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Builders\OfficeBuilder;
use Tests\TestCase;

class UserInfoTabTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_update_user()
    {
        $john   = User::factory()->create(['role' => Role::ADMIN]);
        $office = OfficeBuilder::build()->withManager()->region()->save()->get();
        $mary   = $office->officeManager;

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.first_name', 'Mary')
            ->set('user.last_name', 'Ann')
            ->set('user.office_id', '')
            ->set('user.phone_number', '(11) 1111-1111')
            ->call('update')
            ->assertSessionHas('alert')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id'           => $mary->id,
            'first_name'   => 'Mary',
            'last_name'    => 'Ann',
            'office_id'    => null,
            'phone_number' => '1111111111',
        ]);
    }

    /** @test */
    public function it_should_update_override()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $ann  = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $poll = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $zack = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $office = OfficeBuilder::build()->withManager()->region()->save()->get();
        $mary   = $office->officeManager;

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('userOverride.recruiter_id', $john->id)
            ->set('userOverride.region_manager_id', $poll->id)
            ->set('userOverride.department_manager_id', $ann->id)
            ->call('saveOverride')
            ->assertDispatchedBrowserEvent('show-alert')
            ->assertSet('openedTab', 'payInfo')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id'                    => $mary->id,
            'recruiter_id'          => $john->id,
            'region_manager_id'     => $poll->id,
            'department_manager_id' => $ann->id,
        ]);
    }

    /** @test */
    public function it_should_be_possible_to_change_tab()
    {
        $john   = User::factory()->create(['role' => Role::ADMIN]);
        $office = OfficeBuilder::build()->withManager()->region()->save()->get();
        $mary   = $office->officeManager;

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->assertSet('openedTab', 'userInfo')
            ->call('changeTab', 'orgInfo')
            ->assertSet('openedTab', 'orgInfo');
    }

    /** @test */
    public function it_should_be_possible_selected_department()
    {
        $john   = User::factory()->create(['role' => Role::ADMIN]);
        $office = OfficeBuilder::build()->withManager()->region()->save()->get();
        $mary   = $office->officeManager;

        $newDepartment = Department::factory()->create([
            'department_manager_id' => $john->id
        ]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->assertSet('selectedDepartmentId', $mary->department_id)
            ->call('changeDepartment', $newDepartment->id)
            ->assertSet('selectedDepartmentId', $newDepartment->id);
    }

    /** @test */
    public function it_should_dispatch_an_event_when_cannot_change_role()
    {
        $john   = User::factory()->create(['role' => Role::ADMIN]);
        $office = OfficeBuilder::build()->withManager()->region()->save()->get();
        $mary   = $office->officeManager;

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->call('changeRole', Role::ADMIN)
            ->assertEmitted('app:modal')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_change_user_pay_when_change_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::SETTER]);

        Rates::factory()->create([
            'role' => Role::ADMIN,
            'rate' => 20
        ]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->call('changeRole', Role::ADMIN)
            ->assertSet('user.pay', 20)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_require_user_first_name()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.first_name', '')
            ->call('update')
            ->assertHasErrors(['user.first_name' => 'required']);
    }

    /** @test */
    public function it_should_require_user_last_name()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.last_name', '')
            ->call('update')
            ->assertHasErrors(['user.last_name' => 'required']);
    }

    /** @test */
    public function it_should_require_a_valid_user_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.role', Str::random())
            ->call('update')
            ->assertHasErrors(['user.role' => 'in']);
    }

    /** @test */
    public function it_should_require_an_office_id_that_department_has()
    {
        $john   = User::factory()->create(['role' => Role::ADMIN]);
        $mary   = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $office = OfficeBuilder::build()->withManager()->region()->save()->get();

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.office_id', 99)
            ->set('user.department_id', $office->region->department_id)
            ->call('update')
            ->assertHasErrors('user.office_id');
    }

    /** @test */
    public function it_should_require_an_existent_department_id()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.department_id', 99)
            ->call('update')
            ->assertHasErrors(['user.department_id' => 'exists']);
    }

    /** @test */
    public function it_should_require_an_user_email()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.email', '')
            ->call('update')
            ->assertHasErrors(['user.email' => 'required']);
    }

    /** @test */
    public function it_should_require_a_valid_user_email()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.email', 'sample@.com')
            ->call('update')
            ->assertHasErrors(['user.email' => 'email']);
    }

    /** @test */
    public function it_should_require_a_unique_user_email()
    {
        User::factory()->create(['email' => 'sample@mail.com']);

        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.email', 'sample@mail.com')
            ->call('update')
            ->assertHasErrors(['user.email' => 'unique']);
    }

    /** @test */
    public function it_should_prevent_that_recruiter_id_has_the_same_id_of_edited_user()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('userOverride.recruiter_id', $mary->id)
            ->call('update')
            ->assertHasErrors(['userOverride.recruiter_id' => 'not_in']);
    }

    /** @test */
    public function it_should_prevent_update_if_office_manager_id_is_from_another_department()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $mary = User::factory()->create([
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => Department::factory()->create()->id,
        ]);
        $zack = User::factory()->create([
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => Department::factory()->create()->id,
        ]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('userOverride.office_manager_id', $zack->id)
            ->call('update')
            ->assertHasErrors(['userOverride.office_manager_id' => 'in']);
    }

    /** @test */
    public function it_should_allow_admin_see_all_users()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, [
            'user' => User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]),
        ])->assertOk();

        Livewire::test(UserInfoTab::class, [
            'user' => User::factory()->create(['role' => Role::REGION_MANAGER]),
        ])->assertOk();

        Livewire::test(UserInfoTab::class, [
            'user' => User::factory()->create(['role' => Role::OFFICE_MANAGER]),
        ])->assertOk();

        Livewire::test(UserInfoTab::class, [
            'user' => User::factory()->create(['role' => Role::SALES_REP]),
        ])->assertOk();

        Livewire::test(UserInfoTab::class, [
            'user' => User::factory()->create(['role' => Role::SETTER]),
        ])->assertOk();
    }

    /** @test */
    public function it_should_forbidden_department_manager_see_a_user_that_he_is_not_managing()
    {
        $john       = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $department = Department::factory()->create(['department_manager_id' => $john->id]);

        $john->update(['department_id' => $department->id]);

        $dummy = User::factory()->create(['role' => Role::SETTER]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $dummy])->assertForbidden();
    }

    /** @test */
    public function it_should_forbidden_region_manager_see_a_user_that_he_is_not_managing()
    {
        $office01 = OfficeBuilder::build()->withManager()->region()->save()->get();
        $office02 = OfficeBuilder::build()->withManager()->region()->save()->get();

        $dummy01 = User::factory()->create(['role' => Role::SETTER, 'office_id' => $office01->id]);
        $dummy02 = User::factory()->create(['role' => Role::SETTER, 'office_id' => $office02->id]);

        $this->actingAs($office02->region->regionManager);

        Livewire::test(UserInfoTab::class, ['user' => $dummy01])->assertForbidden();
        Livewire::test(UserInfoTab::class, ['user' => $dummy02])->assertOk();
    }

    /** @test */
    public function it_should_forbidden_office_manager_see_a_user_that_he_is_not_managing()
    {
        $office01 = OfficeBuilder::build()->withManager()->region()->save()->get();
        $office02 = OfficeBuilder::build()->withManager()->region()->save()->get();

        $dummy01 = User::factory()->create(['role' => Role::SETTER, 'office_id' => $office02->id]);
        $dummy02 = User::factory()->create(['role' => Role::SETTER, 'office_id' => $office01->id]);

        $this->actingAs($office01->officeManager);

        Livewire::test(UserInfoTab::class, ['user' => $dummy01])->assertForbidden();
        Livewire::test(UserInfoTab::class, ['user' => $dummy02])->assertOk();
    }
}
