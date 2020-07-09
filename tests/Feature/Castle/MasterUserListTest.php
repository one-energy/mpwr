<?php

namespace Tests\Feature\Castle;

use App\Http\Livewire\Castle\Masters;
use App\Http\Livewire\Castle\Users;
use App\Models\User;
use Livewire\Livewire;
use Tests\Builders\UserBuilder;
use Tests\Feature\FeatureTest;

class MasterUserListTest extends FeatureTest
{
    /** @test */
    public function only_masters_can_list()
    {
        $user = (new UserBuilder)->save()->get();

        $this->actingAs($user)
            ->get(route('castle.masters.index'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_be_able_to_list_all_masters_of_the_castle()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        (new UserBuilder)->asMaster()->make(10)->save();
        $nonMasters = (new UserBuilder)->make(10)->save()->get();

        $this->actingAs($master);
        $response = Livewire::test(Masters::class);

        foreach ($nonMasters as $nonMaster) {
            $response->assertDontSee($nonMaster->email);
        }

        $masters = User::masters()->get();

        foreach ($masters as $master) {
            $response->assertSee($master->email);
        }
    }

    /** @test */
    public function it_should_be_able_to_search_for_an_user_by_name()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $joe    = (new UserBuilder)->withName('Joe Doe')->asMaster()->save()->get();
        $jane   = (new UserBuilder)->withName('Jane Doe')->asMaster()->save()->get();
        $alpha  = (new UserBuilder)->withName('Alpha Smith')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('search', 'Doe')
            ->assertSee($joe->name)
            ->assertSee($jane->name)
            ->assertDontSee($alpha->name);

        Livewire::test(Masters::class)
            ->set('search', 'alph')
            ->assertDontSee($joe->name)
            ->assertDontSee($jane->name)
            ->assertSee($alpha->name);
    }

    /** @test */
    public function it_should_be_able_to_search_for_an_user_by_email()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $joe    = (new UserBuilder)->withEmail('joe@doe.com')->asMaster()->save()->get();
        $jane   = (new UserBuilder)->withEmail('jane@doe.com')->asMaster()->save()->get();
        $alpha  = (new UserBuilder)->withEmail('alpha@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('search', 'Doe')
            ->assertSee($joe->name)
            ->assertSee($jane->name)
            ->assertDontSee($alpha->name);

        Livewire::test(Masters::class)
            ->set('search', 'smith.com')
            ->assertDontSee($joe->name)
            ->assertDontSee($jane->name)
            ->assertSee($alpha->name);
    }

    /** @test */
    public function it_should_be_able_to_filter_by_email_or_name()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $joe    = (new UserBuilder)->withName('Joe Doe')->withEmail('joe@doe.com')->asMaster()->save()->get();
        $jane   = (new UserBuilder)->withName('Jane Doe')->withEmail('jane@doe.com')->asMaster()->save()->get();
        $david  = (new UserBuilder)->withName('David Doe')->withEmail('david@smith.com')->asMaster()->save()->get();
        $alpha  = (new UserBuilder)->withName('Alpha Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('search', 'Doe')
            ->assertSee($joe->name)
            ->assertSee($jane->name)
            ->assertSee($david->name)
            ->assertDontSee($alpha->name);

        Livewire::test(Masters::class)
            ->set('search', 'smith.com')
            ->assertDontSee($joe->name)
            ->assertDontSee($jane->name)
            ->assertSee($david->name)
            ->assertSee($alpha->name);
    }

    /** @test */
    public function it_should_bring_everything_if_search_is_empty()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $joe    = (new UserBuilder)->withName('Joe Doe')->withEmail('joe@doe.com')->asMaster()->save()->get();
        $jane   = (new UserBuilder)->withName('Jane Doe')->withEmail('jane@doe.com')->asMaster()->save()->get();
        $david  = (new UserBuilder)->withName('David Doe')->withEmail('david@smith.com')->asMaster()->save()->get();
        $alpha  = (new UserBuilder)->withName('Alpha Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('search', '')
            ->assertSee($master->name)
            ->assertSee($joe->name)
            ->assertSee($jane->name)
            ->assertSee($david->name)
            ->assertSee($alpha->name);
    }

    /** @test */
    public function it_should_return_a_paginated_search()
    {
        $alpha    = (new UserBuilder)->withName('Alpha Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();
        $beta     = (new UserBuilder)->withName('Beta Smith')->withEmail('beta@smith.com')->asMaster()->save()->get();
        $charlie  = (new UserBuilder)->withName('Charlie Smith')->withEmail('charlie@smith.com')->asMaster()->save()->get();
        $delta    = (new UserBuilder)->withName('Delta Smith')->withEmail('delta@smith.com')->asMaster()->save()->get();
        $echo     = (new UserBuilder)->withName('Echo Smith')->withEmail('echo@smith.com')->asMaster()->save()->get();
        $foxtrot  = (new UserBuilder)->withName('Foxtrot Smith')->withEmail('foxtrot@smith.com')->asMaster()->save()->get();
        $golf     = (new UserBuilder)->withName('Golf Smith')->withEmail('golf@smith.com')->asMaster()->save()->get();
        $hotel    = (new UserBuilder)->withName('Hotel Smith')->withEmail('hotel@smith.com')->asMaster()->save()->get();
        $india    = (new UserBuilder)->withName('India Smith')->withEmail('india@smith.com')->asMaster()->save()->get();
        $juliett  = (new UserBuilder)->withName('Juliett Smith')->withEmail('juliett@smith.com')->asMaster()->save()->get();
        $kilo     = (new UserBuilder)->withName('Kilo Smith')->withEmail('kilo@smith.com')->asMaster()->save()->get();
        $lima     = (new UserBuilder)->withName('Lima Smith')->withEmail('lima@smith.com')->asMaster()->save()->get();
        $master   = (new UserBuilder)->withName('Master')->withEmail('master@master.com')->asMaster()->save()->get();
        $november = (new UserBuilder)->withName('November Smith')->withEmail('november@smith.com')->asMaster()->save()->get();
        $oscar    = (new UserBuilder)->withName('Oscar Smith')->withEmail('oscar@smith.com')->asMaster()->save()->get();
        $victor   = (new UserBuilder)->withName('Victor Smith')->withEmail('victor@smith.com')->asMaster()->save()->get();
        $whiskey  = (new UserBuilder)->withName('Whiskey Smith')->withEmail('whiskey@smith.com')->asMaster()->save()->get();
        $xray     = (new UserBuilder)->withName('Xray Smith')->withEmail('xray@smith.com')->asMaster()->save()->get();
        $yankee   = (new UserBuilder)->withName('Yankee Smith')->withEmail('yankee@smith.com')->asMaster()->save()->get();
        $zulu     = (new UserBuilder)->withName('Zulu Smith')->withEmail('zulu@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->assertSee($alpha->name)
            ->assertSee($beta->name)
            ->assertSee($charlie->name)
            ->assertSee($delta->name)
            ->assertSee($echo->name)
            ->assertSee($foxtrot->name)
            ->assertSee($golf->name)
            ->assertSee($hotel->name)
            ->assertSee($india->name)
            ->assertSee($juliett->name)
            ->assertSee($kilo->name)
            ->assertSee($lima->name)
            ->assertSee($master->name)
            ->assertSee($november->name)
            ->assertSee($oscar->name)
            ->assertDontSee($victor->name)
            ->assertDontSee($whiskey->name)
            ->assertDontSee($xray->name)
            ->assertDontSee($yankee->name)
            ->assertDontSee($zulu->name);
    }

    /** @test */
    public function it_should_be_able_to_order_by_name()
    {
        $master  = (new UserBuilder)->withName('Master')->withEmail('master@master.com')->asMaster()->save()->get();
        $alpha   = (new UserBuilder)->withName('Alpha Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();
        $beta    = (new UserBuilder)->withName('Beta Smith')->withEmail('beta@smith.com')->asMaster()->save()->get();
        $charlie = (new UserBuilder)->withName('Charlie Smith')->withEmail('charlie@smith.com')->asMaster()->save()->get();
        $delta   = (new UserBuilder)->withName('Delta Smith')->withEmail('delta@smith.com')->asMaster()->save()->get();
        $echo    = (new UserBuilder)->withName('Echo Smith')->withEmail('echo@smith.com')->asMaster()->save()->get();
        $victor  = (new UserBuilder)->withName('Victor Smith')->withEmail('victor@smith.com')->asMaster()->save()->get();
        $whiskey = (new UserBuilder)->withName('Whiskey Smith')->withEmail('whiskey@smith.com')->asMaster()->save()->get();
        $xray    = (new UserBuilder)->withName('Xray Smith')->withEmail('xray@smith.com')->asMaster()->save()->get();
        $yankee  = (new UserBuilder)->withName('Yankee Smith')->withEmail('yankee@smith.com')->asMaster()->save()->get();
        $zulu    = (new UserBuilder)->withName('Zulu Smith')->withEmail('zulu@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('sortBy', 'name')
            ->set('perPage', 5)
            ->assertSee($alpha->name)
            ->assertSee($beta->name)
            ->assertSee($charlie->name)
            ->assertSee($delta->name)
            ->assertSee($echo->name)
            ->assertDontSee($victor->name)
            ->assertDontSee($whiskey->name)
            ->assertDontSee($xray->name)
            ->assertDontSee($yankee->name)
            ->assertDontSee($zulu->name);

        Livewire::test(Masters::class)
            ->set('sortBy', 'name')
            ->set('sortDirection', 'asc')
            ->set('perPage', 5)
            ->assertSee($alpha->name)
            ->assertSee($beta->name)
            ->assertSee($charlie->name)
            ->assertSee($delta->name)
            ->assertSee($echo->name)
            ->assertDontSee($victor->name)
            ->assertDontSee($whiskey->name)
            ->assertDontSee($xray->name)
            ->assertDontSee($yankee->name)
            ->assertDontSee($zulu->name);

        Livewire::test(Masters::class)
            ->set('sortBy', 'name')
            ->set('sortDirection', 'desc')
            ->set('perPage', 5)
            ->assertDontSee($alpha->name)
            ->assertDontSee($beta->name)
            ->assertDontSee($charlie->name)
            ->assertDontSee($delta->name)
            ->assertDontSee($echo->name)
            ->assertSee($victor->name)
            ->assertSee($whiskey->name)
            ->assertSee($xray->name)
            ->assertSee($yankee->name)
            ->assertSee($zulu->name);
    }

    /** @test */
    public function it_should_be_able_to_order_by_email()
    {
        $master  = (new UserBuilder)->withName('Master')->withEmail('master@master.com')->asMaster()->save()->get();
        $alpha   = (new UserBuilder)->withName('Alpha Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();
        $beta    = (new UserBuilder)->withName('Beta Smith')->withEmail('beta@smith.com')->asMaster()->save()->get();
        $charlie = (new UserBuilder)->withName('Charlie Smith')->withEmail('charlie@smith.com')->asMaster()->save()->get();
        $delta   = (new UserBuilder)->withName('Delta Smith')->withEmail('delta@smith.com')->asMaster()->save()->get();
        $echo    = (new UserBuilder)->withName('Echo Smith')->withEmail('echo@smith.com')->asMaster()->save()->get();
        $victor  = (new UserBuilder)->withName('Victor Smith')->withEmail('victor@smith.com')->asMaster()->save()->get();
        $whiskey = (new UserBuilder)->withName('Whiskey Smith')->withEmail('whiskey@smith.com')->asMaster()->save()->get();
        $xray    = (new UserBuilder)->withName('Xray Smith')->withEmail('xray@smith.com')->asMaster()->save()->get();
        $yankee  = (new UserBuilder)->withName('Yankee Smith')->withEmail('yankee@smith.com')->asMaster()->save()->get();
        $zulu    = (new UserBuilder)->withName('Zulu Smith')->withEmail('zulu@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('sortBy', 'email')
            ->set('perPage', 5)
            ->assertSee($alpha->name)
            ->assertSee($beta->name)
            ->assertSee($charlie->name)
            ->assertSee($delta->name)
            ->assertSee($echo->name)
            ->assertDontSee($victor->name)
            ->assertDontSee($whiskey->name)
            ->assertDontSee($xray->name)
            ->assertDontSee($yankee->name)
            ->assertDontSee($zulu->name);

        Livewire::test(Masters::class)
            ->set('sortBy', 'email')
            ->set('sortDirection', 'asc')
            ->set('perPage', 5)
            ->assertSee($alpha->name)
            ->assertSee($beta->name)
            ->assertSee($charlie->name)
            ->assertSee($delta->name)
            ->assertSee($echo->name)
            ->assertDontSee($victor->name)
            ->assertDontSee($whiskey->name)
            ->assertDontSee($xray->name)
            ->assertDontSee($yankee->name)
            ->assertDontSee($zulu->name);

        Livewire::test(Masters::class)
            ->set('sortBy', 'email')
            ->set('sortDirection', 'desc')
            ->set('perPage', 5)
            ->assertDontSee($alpha->name)
            ->assertDontSee($beta->name)
            ->assertDontSee($charlie->name)
            ->assertDontSee($delta->name)
            ->assertDontSee($echo->name)
            ->assertSee($victor->name)
            ->assertSee($whiskey->name)
            ->assertSee($xray->name)
            ->assertSee($yankee->name)
            ->assertSee($zulu->name);
    }

    /** @test */
    public function it_should_be_able_to_set_the_number_of_records_per_page()
    {
        $alpha   = (new UserBuilder)->withName('Alpha Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();
        $beta    = (new UserBuilder)->withName('Beta Smith')->withEmail('beta@smith.com')->asMaster()->save()->get();
        $charlie = (new UserBuilder)->withName('Charlie Smith')->withEmail('charlie@smith.com')->asMaster()->save()->get();
        $delta   = (new UserBuilder)->withName('Delta Smith')->withEmail('delta@smith.com')->asMaster()->save()->get();
        $echo    = (new UserBuilder)->withName('Echo Smith')->withEmail('echo@smith.com')->asMaster()->save()->get();
        $foxtrot = (new UserBuilder)->withName('Foxtrot Smith')->withEmail('foxtrot@smith.com')->asMaster()->save()->get();
        $golf    = (new UserBuilder)->withName('Golf Smith')->withEmail('golf@smith.com')->asMaster()->save()->get();
        $hotel   = (new UserBuilder)->withName('Hotel Smith')->withEmail('hotel@smith.com')->asMaster()->save()->get();
        $india   = (new UserBuilder)->withName('India Smith')->withEmail('india@smith.com')->asMaster()->save()->get();
        $juliett = (new UserBuilder)->withName('Juliett Smith')->withEmail('juliett@smith.com')->asMaster()->save()->get();
        $master  = (new UserBuilder)->withName('Super Master Test')->withEmail('master@master.com')->asMaster()->save()->get();
        $victor  = (new UserBuilder)->withName('Victor Smith')->withEmail('victor@smith.com')->asMaster()->save()->get();
        $whiskey = (new UserBuilder)->withName('Whiskey Smith')->withEmail('whiskey@smith.com')->asMaster()->save()->get();
        $xray    = (new UserBuilder)->withName('Xray Smith')->withEmail('xray@smith.com')->asMaster()->save()->get();
        $yankee  = (new UserBuilder)->withName('Yankee Smith')->withEmail('yankee@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('perPage', 5)
            ->assertSee($alpha->name)
            ->assertSee($beta->name)
            ->assertSee($charlie->name)
            ->assertSee($delta->name)
            ->assertSee($echo->name)
            ->assertDontSee($foxtrot->name)
            ->assertDontSee($golf->name)
            ->assertDontSee($hotel->name)
            ->assertDontSee($india->name)
            ->assertDontSee($juliett->name)
            ->assertDontSee($master->name)
            ->assertDontSee($victor->name)
            ->assertDontSee($whiskey->name)
            ->assertDontSee($xray->name)
            ->assertDontSee($yankee->name);

        Livewire::test(Masters::class)
            ->set('perPage', 15)
            ->assertSee($alpha->name)
            ->assertSee($beta->name)
            ->assertSee($charlie->name)
            ->assertSee($delta->name)
            ->assertSee($echo->name)
            ->assertSee($foxtrot->name)
            ->assertSee($golf->name)
            ->assertSee($hotel->name)
            ->assertSee($india->name)
            ->assertSee($juliett->name)
            ->assertSee($master->name)
            ->assertSee($victor->name)
            ->assertSee($whiskey->name)
            ->assertSee($xray->name)
            ->assertSee($yankee->name);
    }

    /** @test */
    public function it_should_be_able_to_filter_users_by_a_given_team()
    {
        $joe  = (new UserBuilder)->withATeam()->withName('Joe Doe')->save()->get();
        $jane = (new UserBuilder)->withATeam()->withName('Jane Doe')->save()->get();

        $team = $joe->teams()->first();

        Livewire::test(Users::class)
            ->set('team', $team->id)
            ->assertSee($joe->name)
            ->assertDontSee($jane->name);

        $this->assertTrue(true);
    }
}
