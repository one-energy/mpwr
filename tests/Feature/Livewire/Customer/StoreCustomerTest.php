<?php

namespace Tests\Feature\Livewire\Customer;

use App\Enum\Role;
use App\Http\Livewire\Customer\Create;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Financing;
use App\Models\User;
use App\Notifications\SalesRepWithoutManagerId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\Builders\OfficeBuilder;
use Tests\TestCase;

class StoreCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_store_a_new_customer()
    {
        Notification::fake();

        $john     = User::factory()->create(['role' => Role::ADMIN]);
        $setter   = User::factory()->create(['role' => Role::SETTER]);
        $salesRep = User::factory()->create(['role' => Role::SALES_REP]);
        /** @var Customer $customer */
        $customer = Customer::factory()->make([
            'setter_id'    => $setter->id,
            'sales_rep_id' => $salesRep->id,
            'opened_by_id' => $john->id,
        ]);

        Department::factory()->create();

        $this->actingAs($john);

        Livewire::test(Create::class, [
            'bills'    => Customer::BILLS,
            'customer' => $customer,
        ])
            ->set('customer.first_name', 'John')
            ->set('customer.last_name', 'Doe')
            ->set('customer.system_size', 1)
            ->set('customer.bill', 1)
            ->set('customer.adders', 1)
            ->set('customer.date_of_sale', today())
            ->set('customer.epc', 1)
            ->set('customer.financing_id', Financing::factory()->create()->id)
            ->set('customer.setter_id', $setter->id)
            ->set('customer.sales_rep_id', $salesRep->id)
            ->set('customer.sales_rep_fee', 1)
            ->call('store');

        $this->assertDatabaseCount('customers', 1);
        $this->assertDatabaseHas('customers', [
            'opened_by_id' => $john->id,
            'setter_id'    => $setter->id,
            'sales_rep_id' => $salesRep->id,
        ]);
    }

    /** @test */
    public function it_should_notify_admin_if_sales_rep_has_some_manager_id_null()
    {
        Notification::fake();

        $john     = User::factory()->create(['role' => Role::ADMIN]);
        $setter   = User::factory()->create(['role' => Role::SETTER]);
        $salesRep = User::factory()->create(['role' => Role::SALES_REP]);
        /** @var Customer $customer */
        $customer = Customer::factory()->make([
            'setter_id'    => $setter->id,
            'sales_rep_id' => $salesRep->id,
            'opened_by_id' => $john->id,
        ]);

        Department::factory()->create();

        $this->actingAs($john);

        Notification::assertNothingSent();

        Livewire::test(Create::class, [
            'bills'    => Customer::BILLS,
            'customer' => $customer,
        ])
            ->set('customer.first_name', 'John')
            ->set('customer.last_name', 'Doe')
            ->set('customer.system_size', 1)
            ->set('customer.bill', 1)
            ->set('customer.adders', 1)
            ->set('customer.date_of_sale', today())
            ->set('customer.epc', 1)
            ->set('customer.financing_id', Financing::factory()->create()->id)
            ->set('customer.setter_id', $setter->id)
            ->set('customer.sales_rep_id', $salesRep->id)
            ->set('customer.sales_rep_fee', 1)
            ->call('store');

        Notification::assertSentTo($john, SalesRepWithoutManagerId::class);
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
        Department::factory()->create();

        $office   = OfficeBuilder::build()->withManager()->region()->save()->get();
        $customer = Customer::factory()->make();

        $salesRep = User::factory()->create([
            'role'          => Role::SALES_REP,
            'office_id'     => $office->id,
            'department_id' => $office->region->department->id,
        ]);

        $setter = User::factory()->create([
            'role'          => Role::SETTER,
            'office_id'     => $office->id,
            'department_id' => $office->region->department->id,
        ]);

        $this->actingAs($office->officeManager);

        Livewire::test(Create::class, [
            'bills'    => Customer::BILLS,
            'customer' => $customer,
        ])->assertSet('customer.sales_rep_id', $office->officeManager->id);

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
