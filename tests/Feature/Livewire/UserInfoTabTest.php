<?php

namespace Tests\Feature\Livewire;

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
        $mary = User::factory()->create(['role' => 'Manager']);

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
        $mary = User::factory()->create(['role' => 'Manager']);

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
        $mary = User::factory()->create(['role' => 'Manager']);

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
        $mary = User::factory()->create(['role' => 'Manager']);

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
        $mary = User::factory()->create(['role' => 'Manager']);

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
        $mary = User::factory()->create(['role' => 'Manager']);

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
        $mary = User::factory()->create(['role' => 'Manager']);

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
        $mary = User::factory()->create(['role' => 'Manager']);

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
}
