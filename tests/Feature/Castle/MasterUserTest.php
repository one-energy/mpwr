<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
use App\Models\User;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class MasterUserTest extends FeatureTest
{
    /** @test */
    public function only_master_users_should_enter_the_castle()
    {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $admin = factory(User::class)->create([
            "role" => "Admin",
            "department_id" => $department->id
        ]);
        $owner = factory(User::class)->create([
            "role" => "Owner",
            "department_id" => $department->id
        ]);


        $this->actingAs($departmentManager)
            ->get(route('castle.users.index'))
            ->assertSuccessful();

        $this->actingAs($admin)
            ->get(route('castle.users.index'))
            ->assertSuccessful();

        $this->actingAs($owner)
            ->get(route('castle.users.index'))
            ->assertSuccessful();
    }

   
    /* public function masters_of_the_castle_should_be_redirect_strait_to_the_castle_after_login()
    {
        $master = (new UserBuilder())->asMaster()
            ->withEmail('master-of@the-castle.com')
            ->withPassword('sauron')
            ->save()->get();

        $this->post(route('login'), [
            'email'    => 'master-of@the-castle.com',
            'password' => 'sauron',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($master);
    } */
}
