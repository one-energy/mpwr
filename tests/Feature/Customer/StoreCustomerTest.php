<?php

namespace Tests\Feature\Customer;

use App\Enum\Role;
use App\Http\Livewire\Customer\Create;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
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

    /** @test */
    public function it_should_start_with_sales_rep_setted_with_logged_user()
    {
        $officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $department = Department::factory()->create();
        $office     = Office::factory()->create([
            'region_id' => Region::factory()->create(['department_id' => $department->id]),
        ]);

        $officeManager->update([
            'department_id' => $department->id,
            'office_id'     => $office->id,
        ]);

        $salesRep = User::factory()->create([
            'role'          => Role::SALES_REP,
            'office_id'     => $office->id,
            'department_id' => $department->id,
        ]);
        $setter   = User::factory()->create([
            'role'          => Role::SETTER,
            'office_id'     => $office->id,
            'department_id' => $department->id,
        ]);

        $customer = Customer::factory()->make();

        Department::factory()->create();

        $this->actingAs($officeManager);

        Livewire::test(Create::class, [
            'bills'    => Customer::BILLS,
            'customer' => $customer,
        ])->assertSet('customer.sales_rep_id', $officeManager->id);

        $this->actingAs($salesRep);

        Livewire::test(Create::class, [
            'bills'    => Customer::BILLS,
            'customer' => $customer,
        ])->assertSet('customer.sales_rep_id', $salesRep->id);

        $this->actingAs($setter);

        Livewire::test(Create::class, [
            'bills'    => Customer::BILLS,
            'customer' => $customer,
        ])->assertSet('customer.sales_rep_id', $setter->id);
    }

    /** @test */
    public function it_should_set_self_gen()
    {
        $department = Department::factory()->create();
        $setter     = User::factory()->create(['role' => Role::SETTER, 'department_id' => $department->id]);
        $customer   = Customer::factory()->make();

        Department::factory()->create();

        $this->actingAs($setter);

        Livewire::test(Create::class, [
            'bills'    => Customer::BILLS,
            'customer' => $customer,
        ])
            ->set('customer.setter_fee', 10)
            ->set('customer.setter_id', null)
            ->assertSet('customer.setter_fee', 0);
    }
}
