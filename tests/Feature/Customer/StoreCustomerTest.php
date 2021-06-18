<?php

namespace Tests\Feature\Customer;

use App\Http\Livewire\Customer\Create;
use App\Models\Customer;
use App\Models\Department;
use App\Models\User;
use App\Enum\Role;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoreCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $this->actingAs(User::factory()->create(['role' => Role::SETTER]))
            ->get(route('customers.create'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        [$departmentManager] = $this->createVP();

        $this->actingAs($departmentManager)
            ->get(route('customers.create'))
            ->assertOk()
            ->assertViewIs('customer.create');
    }

    /** @test */
    public function it_should_store_a_new_customer()
    {
        $john     = User::factory()->create(['role' => Role::ADMIN]);
        $user     = User::factory()->create();
        $userOne  = User::factory()->create(['role' => Role::SETTER]);
        $userTwo  = User::factory()->create(['role' => Role::SALES_REP]);
        $customer = Customer::factory()->make([
            'first_name'          => 'First Name',
            'last_name'           => 'Last Name',
            'bill'                => 'Bill',
            'financing_id'        => 1,
            'opened_by_id'        => $user->id,
            'system_size'         => 0,
            'adders'              => '',
            'epc'                 => '',
            'setter_id'           => $userOne->id,
            'setter_fee'          => 20,
            'sales_rep_id'        => $userTwo->id,
            'sales_rep_fee'       => 20,
            'sales_rep_comission' => 0,
            'commission'          => '',
            'created_at'          => Carbon::now()->timestamp,
            'updated_at'          => Carbon::now()->timestamp,
            'is_active'           => true,
        ]);

        Department::factory()->create();

        $this->actingAs($john);

        Livewire::test(Create::class, [
            'bills'    => Customer::BILLS,
            'customer' => $customer,
        ])
            ->call('store')
            ->assertSee($customer->first_name);
    }

    /** @test */
    public function it_should_require_some_fields_to_store_a_new_customer()
    {
        $john     = User::factory()->create(['role' => Role::ADMIN]);
        $customer = Customer::factory()->make();

        Department::factory()->create();

        $this->actingAs($john);

        Livewire::test(Create::class, [
            'bills'    => Customer::BILLS,
            'customer' => $customer,
        ])
            ->call('store')
            ->assertHasErrors([
                'customer.first_name' => 'required',
                'customer.last_name'  => 'required',
                'customer.bill'       => 'required',
            ]);
    }
}
