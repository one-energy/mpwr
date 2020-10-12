<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
use App\Models\User;
use Tests\Builders\RegionBuilder;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;
use Illuminate\Support\Facades\Hash;
use Tests\Builders\OfficeBuilder;

class UserDetailsTest extends FeatureTest
{
    /** @test */
    public function only_master_users_can_see_user_details()
    {
        $departmentManager = factory(User::class)->create([
            "role" => "Department Manager"
        ]);

        $department = factory(Department::class)->create([
            "department_manager_id" => $departmentManager->id   
        ]);
        
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $setter = factory(User::class)->create([
            "role" => "Setter",
            "department_id" => $department->id
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
        $master    = (new UserBuilder)->asMaster()->save()->get();
        $nonMaster = (new UserBuilder)->save()->get();
        $department = factory(Department::class)->create([
            "name" => "Department One"
        ]);

        $master    = factory(User::class)->create([
            "role" => "Admin",
        ]);

        $nonMaster    = factory(User::class)->create([
            "role"          => "Department Manager",
            "department_id" => $department->id
        ]);

        $this->actingAs($master)
            ->get(route('castle.users.edit', $master->id))
            ->assertViewIs('castle.users.edit')
            ->assertSee($master->first_name)
            ->assertSee($master->last_name)
            ->assertSee($master->email);

        $this->actingAs($master)
            ->get(route('castle.users.edit', $nonMaster->id))
            ->assertViewIs('castle.users.edit')
            ->assertSee($nonMaster->first_name)
            ->assertSee($nonMaster->last_name)
            ->assertSee($nonMaster->email);
    }

    /** @test */
    public function it_should_show_the_office_a_user_is_on()
    {
        $master   = (new UserBuilder)->asMaster()->save()->get();
        
        $region   = (new RegionBuilder)->withManager($master)->save()->get();

        $office1  = (new OfficeBuilder)->region($region)->withManager($master)->save()->get();

        $user1    = (new UserBuilder)->withOffice($office1)->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.show', $user1->id))
            ->assertViewIs('castle.users.show')
            ->assertSee($office1->name)
            ->assertSee($user1->first_name);
    }

    /** @test */
    public function it_should_reset_users_password()
    {
        $this->withoutExceptionHandling();
        
        $master      = factory(User::class)->create(['master' => 1]);
        $user        = factory(User::class)->create(['password' => '123456789']);
        $data        = $user->toArray();
        $newPassword = array_merge($data, [
            'new_password'              => '123456789',
            'new_password_confirmation' => '123456789'
        ]);

        $response = $this->actingAs($master)->put(route('castle.users.reset-password', $user->id), $newPassword);

        $response->assertStatus(302);
    }
}
