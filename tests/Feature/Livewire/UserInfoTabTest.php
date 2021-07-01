<?php

namespace Tests\Feature\Livewire;

use App\Enum\Role;
use App\Http\Livewire\Castle\Users\UserInfoTab;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class UserInfoTabTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_require_user_first_name()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Office Manager']);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.first_name', '')
            ->call('update')
            ->assertHasErrors(['user.first_name' => 'required']);
    }

    /** @test */
    public function it_should_require_user_last_name()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Office Manager']);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.last_name', '')
            ->call('update')
            ->assertHasErrors(['user.last_name' => 'required']);
    }

    /** @test */
    public function it_should_require_a_valid_user_role()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Office Manager']);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.role', Str::random())
            ->call('update')
            ->assertHasErrors(['user.role' => 'in']);
    }

    /** @test */
    public function it_should_require_an_office_id_that_department_has()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Office Manager']);

        $manager = User::factory()->create(['role' => 'Manager']);

        $department = Department::factory()->create(['department_manager_id' => $manager->id]);
        $region     = Region::factory()->create([
            'region_manager_id' => $manager->id,
            'department_id'     => $department->id,
        ]);
        Office::factory()->create([
            'office_manager_id' => $manager->id,
            'region_id'         => $region->id,
        ]);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.office_id', 99)
            ->set('user.department_id', $department->id)
            ->call('update')
            ->assertHasErrors('user.office_id');
    }

    /** @test */
    public function it_should_require_an_existent_department_id()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Office Manager']);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.department_id', 99)
            ->call('update')
            ->assertHasErrors(['user.department_id' => 'exists']);
    }

    /** @test */
    public function it_should_require_an_user_email()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Office Manager']);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.email', '')
            ->call('update')
            ->assertHasErrors(['user.email' => 'required']);
    }

    /** @test */
    public function it_should_require_a_valid_user_email()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Office Manager']);

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

        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Office Manager']);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('user.email', 'sample@mail.com')
            ->call('update')
            ->assertHasErrors(['user.email' => 'unique']);
    }

    /** @test */
    public function it_should_prevent_that_recruiter_id_has_the_same_id_of_edited_user()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Office Manager']);

        $this->actingAs($john);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('userOverride.recruiter_id', $mary->id)
            ->call('update')
            ->assertHasErrors(['userOverride.recruiter_id' => 'not_in']);
    }

    /** @test */
    public function it_should_prevent_update_if_office_manager_id_is_from_another_department()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create([
            'role'          => 'Office Manager',
            'department_id' => Department::factory()->create()->id,
        ]);
        $zack = User::factory()->create([
            'role'          => 'Office Manager',
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
            'user' => User::factory()->create(['role' => Role::DEPARTMENT_MANAGER])
        ])->assertOk();

        Livewire::test(UserInfoTab::class, [
            'user' => User::factory()->create(['role' => Role::REGION_MANAGER])
        ])->assertOk();

        Livewire::test(UserInfoTab::class, [
            'user' => User::factory()->create(['role' => Role::OFFICE_MANAGER])
        ])->assertOk();

        Livewire::test(UserInfoTab::class, [
            'user' => User::factory()->create(['role' => Role::SALES_REP])
        ])->assertOk();

        Livewire::test(UserInfoTab::class, [
            'user' => User::factory()->create(['role' => Role::SETTER])
        ])->assertOk();
    }

    /** @test */
    public function it_should_forbidden_department_manager_see_a_user_that_he_is_not_managing()
    {
        [$manager] = $this->createDepartmentManager();
        $dummy = User::factory()->create(['role' => Role::SETTER]);

        $this->actingAs($manager);

        Livewire::test(UserInfoTab::class, ['user' => $dummy])->assertForbidden();
    }

    /** @test */
    public function it_should_forbidden_region_manager_see_a_user_that_he_is_not_managing()
    {
        [$departmentManager, $department] = $this->createDepartmentManager();
        [$regionManager, $region] = $this->createRegionManager($department);
        [$officeManager, $office] = $this->createOfficeManager($region);

        [$departmentManager2, $department2] = $this->createDepartmentManager();
        [$regionManager2, $region2] = $this->createRegionManager($department2);
        [$officeManager2, $office2] = $this->createOfficeManager($region2);

        $dummy01 = User::factory()->create(['role' => Role::SETTER, 'office_id' => $office2->id]);
        $dummy02 = User::factory()->create(['role' => Role::SETTER, 'office_id' => $office->id]);

        $this->actingAs($regionManager);

        Livewire::test(UserInfoTab::class, ['user' => $dummy01])->assertForbidden();
        Livewire::test(UserInfoTab::class, ['user' => $dummy02])->assertOk();
    }

    /** @test */
    public function it_should_forbidden_office_manager_see_a_user_that_he_is_not_managing()
    {
        [$departmentManager, $department] = $this->createDepartmentManager();
        [$regionManager, $region] = $this->createRegionManager($department);
        [$officeManager, $office] = $this->createOfficeManager($region);

        [$departmentManager2, $department2] = $this->createDepartmentManager();
        [$regionManager2, $region2] = $this->createRegionManager($department2);
        [$officeManager2, $office2] = $this->createOfficeManager($region2);

        $dummy01 = User::factory()->create(['role' => Role::SETTER, 'office_id' => $office2->id]);
        $dummy02 = User::factory()->create(['role' => Role::SETTER, 'office_id' => $office->id]);

        $this->actingAs($officeManager);

        Livewire::test(UserInfoTab::class, ['user' => $dummy01])->assertForbidden();
        Livewire::test(UserInfoTab::class, ['user' => $dummy02])->assertOk();
    }

    private function createDepartmentManager()
    {
        $user       = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $department = Department::factory()->create(['department_manager_id' => $user->id]);

        $user->update(['department_id' => $department->id]);

        return [$user, $department];
    }

    private function createRegionManager(Department $department)
    {
        $user   = User::factory()->create([
            'role'          => Role::REGION_MANAGER,
            'department_id' => $department->id,
        ]);
        $region = Region::factory()->create([
            'department_id'     => $department->id,
            'region_manager_id' => $user->id,
        ]);

        return [$user, $region];
    }

    private function createOfficeManager(Region $region)
    {
        $user   = User::factory()->create([
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $region->department_id,
        ]);
        $office = Office::factory()->create([
            'office_manager_id' => $user->id,
            'region_id'         => $region->id
        ]);

        return [$user, $office];
    }
}
