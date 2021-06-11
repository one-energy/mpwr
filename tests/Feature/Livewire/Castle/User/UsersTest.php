<?php

namespace Tests\Feature\Livewire\Castle\User;

use App\Http\Livewire\Castle\Users;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_list_only_office_managers_if_the_authenticated_user_has_office_manager_role()
    {
        $john = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $ann  = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($ann->id);

        /** @var Office $office */
        $office = Office::factory()->create(['region_id' => $region->id]);
        $office->managers()->attach($john->id);


        $mary = User::factory()->create([
            'first_name' => 'Mary',
            'role'       => 'Office Manager',
            'office_id'  => $office->id,
        ]);

        $zack = User::factory()->create([
            'first_name' => 'Zack',
            'role'       => 'Office Manager',
            'office_id'  => $office->id,
        ]);

        $this->actingAs($john);

        Livewire::test(Users::class)
            ->assertSee($mary->full_name)
            ->assertSee($zack->full_name)
            ->assertSeeInOrder([$mary->full_name, $zack->full_name]);
    }

    /** @test */
    public function it_should_show_office_name_if_the_user_belongs_to_an_office()
    {
        $john = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $ann  = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($ann->id);

        /** @var Office $office */
        $office = Office::factory()->create(['region_id' => $region->id]);
        $office->managers()->attach($john->id);

        $mary = User::factory()->create([
            'role'      => Role::OFFICE_MANAGER,
            'office_id' => $office->id,
        ]);

        $this->actingAs($john);

        Livewire::test(Users::class)
            ->assertSee($mary->full_name)
            ->assertSee($mary->office->name);

        $this->assertSame($office->name, $mary->office->name);
    }

    /** @test */
    public function it_should_show_a_dash_in_office_column_if_the_user_not_belongs_to_an_office()
    {
        $mary = User::factory()->create(['role' => 'Admin']);
        $john = User::factory()->create(['role' => 'Office Manager']);

        $this->actingAs($mary);

        Livewire::test(Users::class)
            ->assertSee($john->full_name)
            ->assertSee(html_entity_decode('&#8212;'));
    }

    /** @test */
    public function only_users_that_have_manager_or_regional_manager_or_department_manager_role_can_see_offices_list()
    {
        $mary = User::factory()->create(['role' => Role::ADMIN]);
        $john = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $zack = User::factory()->create(['role' => Role::SETTER]);
        $ann  = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($ann->id);

        /** @var Office $office */
        $office = Office::factory()->create(['region_id' => $region->id]);
        $office->managers()->attach($john->id);

        $this->actingAs($mary);

        /** @var TestableLivewire $livewire */
        $livewire = Livewire::test(Users::class)
            ->call('canSeeOffices', $john);

        $this->assertTrue($livewire->payload['effects']['returns']['canSeeOffices']);

        /** @var TestableLivewire $livewire */
        $livewire = Livewire::test(Users::class)
            ->call('canSeeOffices', $zack);

        $this->assertFalse($livewire->payload['effects']['returns']['canSeeOffices']);
    }
}
