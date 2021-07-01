<?php

namespace Tests\Feature\Castle\User;

use App\Enum\Role;
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
        $user1   = UserBuilder::build()->withOffice($office1)->save()->get();

        $this->actingAs($master)
            ->get(route('castle.users.show', $user1->id))
            ->assertViewIs('castle.users.show')
            ->assertSee($user1->first_name);
    }
}