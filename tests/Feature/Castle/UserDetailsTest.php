<?php

namespace Tests\Feature\Castle;

use App\Models\User;
use Tests\Builders\RegionBuilder;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;
use Illuminate\Support\Facades\Hash;

class UserDetailsTest extends FeatureTest
{
    /** @test */
    public function only_master_users_can_see_user_details()
    {
        $nonMaster = (new UserBuilder)->save()->get();

        $this->actingAs($nonMaster)
            ->get(route('castle.users.edit', $nonMaster->id))
            ->assertForbidden();

        $master = (new UserBuilder)->asMaster()->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.edit', $master->id))
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_show_the_details_for_a_user()
    {
        $master    = (new UserBuilder)->asMaster()->save()->get();
        $nonMaster = (new UserBuilder)->save()->get();

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
    public function it_should_show_the_regions_a_user_is_on()
    {
        $master   = (new UserBuilder)->asMaster()->save()->get();
        $region1  = (new RegionBuilder)->withOwner($master)->save()->get();
        $region2  = (new RegionBuilder)->withOwner($master)->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.show', $master->id))
            ->assertViewIs('castle.users.show')
            ->assertSee($region1->name)
            ->assertSee($region2->name);
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
