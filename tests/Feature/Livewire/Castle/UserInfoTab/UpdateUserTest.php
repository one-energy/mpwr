<?php

namespace Tests\Feature\Livewire\Castle\UserInfoTab;

use App\Http\Livewire\Castle\Users\UserInfoTab;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_detach_departments_if_the_user_has_a_department_manager_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var User $mary */
        $mary = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        $departments = Department::factory()->times(2)->create();
        $departments->each(fn(Department $department) => $mary->managedDepartments()->attach($department->id));

        $this->actingAs($john);

        $this->assertDatabaseCount('user_managed_departments', 2);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->assertSet('selectedRole', $mary->role)
            ->assertSet('showWarningRoleModal', false)
            ->call('changeRole')
            ->assertSet('showWarningRoleModal', true)
            ->set('selectedRole', Role::REGION_MANAGER)
            ->call('changeUserPay')
            ->assertSet('user.role', Role::REGION_MANAGER)
            ->call('update');

        $mary->refresh();

        $this->assertSame(Role::REGION_MANAGER, $mary->role);
        $this->assertCount(0, $mary->managedDepartments);

        $this->assertDatabaseCount('user_managed_departments', 0);
    }

    /** @test */
    public function it_should_detach_offices_if_the_user_has_a_office_manager_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var User $mary */
        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $offices = Office::factory()->times(2)->create();
        $offices->each(fn(Office $office) => $mary->managedOffices()->attach($office->id));

        $this->actingAs($john);

        $this->assertDatabaseCount('user_managed_offices', 2);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('selectedRole', Role::REGION_MANAGER)
            ->call('changeUserPay')
            ->assertSet('user.role', Role::REGION_MANAGER)
            ->call('update');

        $mary->refresh();

        $this->assertSame(Role::REGION_MANAGER, $mary->role);
        $this->assertCount(0, $mary->managedOffices);

        $this->assertDatabaseCount('user_managed_offices', 0);
    }

    /** @test */
    public function it_should_detach_offices_if_the_user_has_a_region_manager_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var User $mary */
        $mary = User::factory()->create(['role' => Role::REGION_MANAGER]);

        $regions = Region::factory()->times(2)->create();
        $regions->each(fn(Region $region) => $mary->managedRegions()->attach($region->id));

        $this->actingAs($john);

        $this->assertDatabaseCount('user_managed_regions', 2);

        Livewire::test(UserInfoTab::class, ['user' => $mary])
            ->set('selectedRole', Role::DEPARTMENT_MANAGER)
            ->call('changeUserPay')
            ->assertSet('user.role', Role::DEPARTMENT_MANAGER)
            ->call('update');

        $mary->refresh();

        $this->assertSame(Role::DEPARTMENT_MANAGER, $mary->role);
        $this->assertCount(0, $mary->managedOffices);

        $this->assertDatabaseCount('user_managed_regions', 0);
    }
}
