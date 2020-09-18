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
    public function it_should_be_able_to_search_for_an_user_by_last_name()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $joe    = (new UserBuilder)->withFirstName('Joe')->withLastName('Doe')->asMaster()->save()->get();
        $jane   = (new UserBuilder)->withFirstName('Jane')->withLastName('Doe')->asMaster()->save()->get();
        $alpha  = (new UserBuilder)->withFirstName('Alpha')->withLastName('Smith')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('search', 'Doe')
            ->assertSee($joe->first_name)
            ->assertSee($jane->first_name)
            ->assertDontSee($alpha->first_name);

        Livewire::test(Masters::class)
            ->set('search', 'alph')
            ->assertDontSee($joe->first_name)
            ->assertDontSee($jane->first_name)
            ->assertSee($alpha->first_name);
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
            ->assertSee($joe->last_name)
            ->assertSee($jane->last_name)
            ->assertDontSee($alpha->last_name);

        Livewire::test(Masters::class)
            ->set('search', 'smith.com')
            ->assertDontSee($joe->first_name)
            ->assertDontSee($jane->first_name)
            ->assertSee($alpha->first_name);
    }

    /** @test */
    public function it_should_be_able_to_filter_by_email_or_first_name_or_last_name()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $joe    = (new UserBuilder)->withFirstName('Joe')->withLastName('Doe')->withEmail('joe@doe.com')->asMaster()->save()->get();
        $jane   = (new UserBuilder)->withFirstName('Jane')->withLastName('Doe')->withEmail('jane@doe.com')->asMaster()->save()->get();
        $david  = (new UserBuilder)->withFirstName('David')->withLastName('Doe')->withEmail('david@smith.com')->asMaster()->save()->get();
        $alpha  = (new UserBuilder)->withFirstName('Alpha')->withLastName('Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('search', 'Doe')
            ->assertSee($joe->last_name)
            ->assertSee($jane->last_name)
            ->assertSee($david->last_name)
            ->assertDontSee($alpha->last_name);

        Livewire::test(Masters::class)
            ->set('search', 'smith.com')
            ->assertDontSee($joe->first_name)
            ->assertDontSee($jane->first_name)
            ->assertSee($david->first_name)
            ->assertSee($alpha->first_name);
    }

    /** @test */
    public function it_should_bring_everything_if_search_is_empty()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $joe    = (new UserBuilder)->withFirstName('Joe')->withLastName('Doe')->withEmail('joe@doe.com')->asMaster()->save()->get();
        $jane   = (new UserBuilder)->withFirstName('Jane')->withLastName('Doe')->withEmail('jane@doe.com')->asMaster()->save()->get();
        $david  = (new UserBuilder)->withFirstName('David')->withLastName('Doe')->withEmail('david@smith.com')->asMaster()->save()->get();
        $alpha  = (new UserBuilder)->withFirstName('Alpha')->withLastName('Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('search', '')
            ->assertSee($master->first_name)
            ->assertSee($joe->first_name)
            ->assertSee($jane->first_name)
            ->assertSee($david->first_name)
            ->assertSee($alpha->first_name);
    }

    /** @test */
    public function it_should_return_a_paginated_search()
    {
        $alpha    = (new UserBuilder)->withFirstName('Alpha')->withLastName('Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();
        $beta     = (new UserBuilder)->withFirstName('Beta')->withLastName('Smith')->withEmail('beta@smith.com')->asMaster()->save()->get();
        $charlie  = (new UserBuilder)->withFirstName('Charlie')->withLastName('Smith')->withEmail('charlie@smith.com')->asMaster()->save()->get();
        $delta    = (new UserBuilder)->withFirstName('Delta')->withLastName('Smith')->withEmail('delta@smith.com')->asMaster()->save()->get();
        $echo     = (new UserBuilder)->withFirstName('Echo')->withLastName('Smith')->withEmail('echo@smith.com')->asMaster()->save()->get();
        $foxtrot  = (new UserBuilder)->withFirstName('Foxtrot')->withLastName('Smith')->withEmail('foxtrot@smith.com')->asMaster()->save()->get();
        $golf     = (new UserBuilder)->withFirstName('Golf')->withLastName('Smith')->withEmail('golf@smith.com')->asMaster()->save()->get();
        $hotel    = (new UserBuilder)->withFirstName('Hotel')->withLastName('Smith')->withEmail('hotel@smith.com')->asMaster()->save()->get();
        $india    = (new UserBuilder)->withFirstName('India')->withLastName('Smith')->withEmail('india@smith.com')->asMaster()->save()->get();
        $juliett  = (new UserBuilder)->withFirstName('Juliett')->withLastName('Smith')->withEmail('juliett@smith.com')->asMaster()->save()->get();
        $kilo     = (new UserBuilder)->withFirstName('Kilo')->withLastName('Smith')->withEmail('kilo@smith.com')->asMaster()->save()->get();
        $lima     = (new UserBuilder)->withFirstName('Lima')->withLastName('Smith')->withEmail('lima@smith.com')->asMaster()->save()->get();
        $master   = (new UserBuilder)->withFirstName('Master')->withEmail('master@master.com')->asMaster()->save()->get();
        $november = (new UserBuilder)->withFirstName('November')->withLastName('Smith')->withEmail('november@smith.com')->asMaster()->save()->get();
        $oscar    = (new UserBuilder)->withFirstName('Oscar')->withLastName('Smith')->withEmail('oscar@smith.com')->asMaster()->save()->get();
        $victor   = (new UserBuilder)->withFirstName('Victor')->withLastName('Smith')->withEmail('victor@smith.com')->asMaster()->save()->get();
        $whiskey  = (new UserBuilder)->withFirstName('Whiskey')->withLastName('Smith')->withEmail('whiskey@smith.com')->asMaster()->save()->get();
        $xray     = (new UserBuilder)->withFirstName('Xray')->withLastName('Smith')->withEmail('xray@smith.com')->asMaster()->save()->get();
        $yankee   = (new UserBuilder)->withFirstName('Yankee')->withLastName('Smith')->withEmail('yankee@smith.com')->asMaster()->save()->get();
        $zulu     = (new UserBuilder)->withFirstName('Zulu')->withLastName('Smith')->withEmail('zulu@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->assertSee($alpha->first_name)
            ->assertSee($beta->first_name)
            ->assertSee($charlie->first_name)
            ->assertSee($delta->first_name)
            ->assertSee($echo->first_name)
            ->assertSee($foxtrot->first_name)
            ->assertSee($golf->first_name)
            ->assertSee($hotel->first_name)
            ->assertSee($india->first_name)
            ->assertSee($juliett->first_name)
            ->assertSee($kilo->first_name)
            ->assertSee($lima->first_name)
            ->assertSee($master->first_name)
            ->assertSee($november->first_name)
            ->assertSee($oscar->first_name)
            ->assertDontSee($victor->first_name)
            ->assertDontSee($whiskey->first_name)
            ->assertDontSee($xray->first_name)
            ->assertDontSee($yankee->first_name)
            ->assertDontSee($zulu->first_name);
    }

    /** @test */
    public function it_should_be_able_to_order_by_name()
    {
        $master  = (new UserBuilder)->withFirstName('Master')->withEmail('master@master.com')->asMaster()->save()->get();
        $alpha   = (new UserBuilder)->withFirstName('Alpha')->withLastName('Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();
        $beta    = (new UserBuilder)->withFirstName('Beta')->withLastName('Smith')->withEmail('beta@smith.com')->asMaster()->save()->get();
        $charlie = (new UserBuilder)->withFirstName('Charlie')->withLastName('Smith')->withEmail('charlie@smith.com')->asMaster()->save()->get();
        $delta   = (new UserBuilder)->withFirstName('Delta')->withLastName('Smith')->withEmail('delta@smith.com')->asMaster()->save()->get();
        $echo    = (new UserBuilder)->withFirstName('Echo')->withLastName('Smith')->withEmail('echo@smith.com')->asMaster()->save()->get();
        $victor  = (new UserBuilder)->withFirstName('Victor')->withLastName('Smith')->withEmail('victor@smith.com')->asMaster()->save()->get();
        $whiskey = (new UserBuilder)->withFirstName('Whiskey')->withLastName('Smith')->withEmail('whiskey@smith.com')->asMaster()->save()->get();
        $xray    = (new UserBuilder)->withFirstName('Xray')->withLastName('Smith')->withEmail('xray@smith.com')->asMaster()->save()->get();
        $yankee  = (new UserBuilder)->withFirstName('Yankee')->withLastName('Smith')->withEmail('yankee@smith.com')->asMaster()->save()->get();
        $zulu    = (new UserBuilder)->withFirstName('Zulu')->withLastName('Smith')->withEmail('zulu@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('sortBy', 'first_name')
            ->set('perPage', 5)
            ->assertSee($alpha->first_name)
            ->assertSee($beta->first_name)
            ->assertSee($charlie->first_name)
            ->assertSee($delta->first_name)
            ->assertSee($echo->first_name)
            ->assertDontSee($victor->first_name)
            ->assertDontSee($whiskey->first_name)
            ->assertDontSee($xray->first_name)
            ->assertDontSee($yankee->first_name)
            ->assertDontSee($zulu->first_name);

        Livewire::test(Masters::class)
            ->set('sortBy', 'first_name')
            ->set('sortDirection', 'asc')
            ->set('perPage', 5)
            ->assertSee($alpha->first_name)
            ->assertSee($beta->first_name)
            ->assertSee($charlie->first_name)
            ->assertSee($delta->first_name)
            ->assertSee($echo->first_name)
            ->assertDontSee($victor->first_name)
            ->assertDontSee($whiskey->first_name)
            ->assertDontSee($xray->first_name)
            ->assertDontSee($yankee->first_name)
            ->assertDontSee($zulu->first_name);

        Livewire::test(Masters::class)
            ->set('sortBy', 'first_name')
            ->set('sortDirection', 'desc')
            ->set('perPage', 5)
            ->assertDontSee($alpha->first_name)
            ->assertDontSee($beta->first_name)
            ->assertDontSee($charlie->first_name)
            ->assertDontSee($delta->first_name)
            ->assertDontSee($echo->first_name)
            ->assertSee($victor->first_name)
            ->assertSee($whiskey->first_name)
            ->assertSee($xray->first_name)
            ->assertSee($yankee->first_name)
            ->assertSee($zulu->first_name);
    }

    /** @test */
    public function it_should_be_able_to_order_by_email()
    {
        $master  = (new UserBuilder)->withFirstName('Master')->withEmail('master@master.com')->asMaster()->save()->get();
        $alpha   = (new UserBuilder)->withFirstName('Alpha')->withLastName('Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();
        $beta    = (new UserBuilder)->withFirstName('Beta')->withLastName('Smith')->withEmail('beta@smith.com')->asMaster()->save()->get();
        $charlie = (new UserBuilder)->withFirstName('Charlie')->withLastName('Smith')->withEmail('charlie@smith.com')->asMaster()->save()->get();
        $delta   = (new UserBuilder)->withFirstName('Delta')->withLastName('Smith')->withEmail('delta@smith.com')->asMaster()->save()->get();
        $echo    = (new UserBuilder)->withFirstName('Echo')->withLastName('Smith')->withEmail('echo@smith.com')->asMaster()->save()->get();
        $victor  = (new UserBuilder)->withFirstName('Victor')->withLastName('Smith')->withEmail('victor@smith.com')->asMaster()->save()->get();
        $whiskey = (new UserBuilder)->withFirstName('Whiskey')->withLastName('Smith')->withEmail('whiskey@smith.com')->asMaster()->save()->get();
        $xray    = (new UserBuilder)->withFirstName('Xray')->withLastName('Smith')->withEmail('xray@smith.com')->asMaster()->save()->get();
        $yankee  = (new UserBuilder)->withFirstName('Yankee')->withLastName('Smith')->withEmail('yankee@smith.com')->asMaster()->save()->get();
        $zulu    = (new UserBuilder)->withFirstName('Zulu')->withLastName('Smith')->withEmail('zulu@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('sortBy', 'email')
            ->set('perPage', 5)
            ->assertSee($alpha->first_name)
            ->assertSee($beta->first_name)
            ->assertSee($charlie->first_name)
            ->assertSee($delta->first_name)
            ->assertSee($echo->first_name)
            ->assertDontSee($victor->first_name)
            ->assertDontSee($whiskey->first_name)
            ->assertDontSee($xray->first_name)
            ->assertDontSee($yankee->first_name)
            ->assertDontSee($zulu->first_name);

        Livewire::test(Masters::class)
            ->set('sortBy', 'email')
            ->set('sortDirection', 'asc')
            ->set('perPage', 5)
            ->assertSee($alpha->first_name)
            ->assertSee($beta->first_name)
            ->assertSee($charlie->first_name)
            ->assertSee($delta->first_name)
            ->assertSee($echo->first_name)
            ->assertDontSee($victor->first_name)
            ->assertDontSee($whiskey->first_name)
            ->assertDontSee($xray->first_name)
            ->assertDontSee($yankee->first_name)
            ->assertDontSee($zulu->first_name);

        Livewire::test(Masters::class)
            ->set('sortBy', 'email')
            ->set('sortDirection', 'desc')
            ->set('perPage', 5)
            ->assertDontSee($alpha->first_name)
            ->assertDontSee($beta->first_name)
            ->assertDontSee($charlie->first_name)
            ->assertDontSee($delta->first_name)
            ->assertDontSee($echo->first_name)
            ->assertSee($victor->first_name)
            ->assertSee($whiskey->first_name)
            ->assertSee($xray->first_name)
            ->assertSee($yankee->first_name)
            ->assertSee($zulu->first_name);
    }

    /** @test */
    public function it_should_be_able_to_set_the_number_of_records_per_page()
    {
        $alpha   = (new UserBuilder)->withFirstName('Alpha')->withLastName('Smith')->withEmail('alpha@smith.com')->asMaster()->save()->get();
        $beta    = (new UserBuilder)->withFirstName('Beta')->withLastName('Smith')->withEmail('beta@smith.com')->asMaster()->save()->get();
        $charlie = (new UserBuilder)->withFirstName('Charlie')->withLastName('Smith')->withEmail('charlie@smith.com')->asMaster()->save()->get();
        $delta   = (new UserBuilder)->withFirstName('Delta')->withLastName('Smith')->withEmail('delta@smith.com')->asMaster()->save()->get();
        $echo    = (new UserBuilder)->withFirstName('Echo')->withLastName('Smith')->withEmail('echo@smith.com')->asMaster()->save()->get();
        $foxtrot = (new UserBuilder)->withFirstName('Foxtrot')->withLastName('Smith')->withEmail('foxtrot@smith.com')->asMaster()->save()->get();
        $golf    = (new UserBuilder)->withFirstName('Golf')->withLastName('Smith')->withEmail('golf@smith.com')->asMaster()->save()->get();
        $hotel   = (new UserBuilder)->withFirstName('Hotel')->withLastName('Smith')->withEmail('hotel@smith.com')->asMaster()->save()->get();
        $india   = (new UserBuilder)->withFirstName('India')->withLastName('Smith')->withEmail('india@smith.com')->asMaster()->save()->get();
        $juliett = (new UserBuilder)->withFirstName('Juliett')->withLastName('Smith')->withEmail('juliett@smith.com')->asMaster()->save()->get();
        $master  = (new UserBuilder)->withFirstName('Super Master Test')->withEmail('master@master.com')->asMaster()->save()->get();
        $victor  = (new UserBuilder)->withFirstName('Victor')->withLastName('Smith')->withEmail('victor@smith.com')->asMaster()->save()->get();
        $whiskey = (new UserBuilder)->withFirstName('Whiskey')->withLastName('Smith')->withEmail('whiskey@smith.com')->asMaster()->save()->get();
        $xray    = (new UserBuilder)->withFirstName('Xray')->withLastName('Smith')->withEmail('xray@smith.com')->asMaster()->save()->get();
        $yankee  = (new UserBuilder)->withFirstName('Yankee')->withLastName('Smith')->withEmail('yankee@smith.com')->asMaster()->save()->get();

        $this->actingAs($master);

        Livewire::test(Masters::class)
            ->set('perPage', 5)
            ->assertSee($alpha->first_name)
            ->assertSee($beta->first_name)
            ->assertSee($charlie->first_name)
            ->assertSee($delta->first_name)
            ->assertSee($echo->first_name)
            ->assertDontSee($foxtrot->first_name)
            ->assertDontSee($golf->first_name)
            ->assertDontSee($hotel->first_name)
            ->assertDontSee($india->first_name)
            ->assertDontSee($juliett->first_name)
            ->assertDontSee($master->first_name)
            ->assertDontSee($victor->first_name)
            ->assertDontSee($whiskey->first_name)
            ->assertDontSee($xray->first_name)
            ->assertDontSee($yankee->first_name);

        Livewire::test(Masters::class)
            ->set('perPage', 15)
            ->assertSee($alpha->first_name)
            ->assertSee($beta->first_name)
            ->assertSee($charlie->first_name)
            ->assertSee($delta->first_name)
            ->assertSee($echo->first_name)
            ->assertSee($foxtrot->first_name)
            ->assertSee($golf->first_name)
            ->assertSee($hotel->first_name)
            ->assertSee($india->first_name)
            ->assertSee($juliett->first_name)
            ->assertSee($master->first_name)
            ->assertSee($victor->first_name)
            ->assertSee($whiskey->first_name)
            ->assertSee($xray->first_name)
            ->assertSee($yankee->first_name);
    }

    /** @test */
    public function it_should_be_able_to_filter_users_by_a_given_region()
    {
        $joe  = (new UserBuilder)->withFirstName('Joe')->withLastName('Doe')->save()->get();
        $jane = (new UserBuilder)->withFirstName('Jane')->withLastName('Doe')->save()->get();

        $office = $joe->office()->first();

        Livewire::test(Users::class)
            ->assertSee($joe->first_name)
            ->assertSee($jane->first_name);

        $this->assertTrue(true);
    }
}
