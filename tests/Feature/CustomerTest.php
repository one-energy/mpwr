<?php

namespace Tests\Feature;

use App\Http\Livewire\Customer\Create;
use App\Http\Livewire\Customer\Edit;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Financer;
use App\Models\Financing;
use App\Models\Rates;
use App\Models\Term;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'Admin']);
        Department::factory()->count(6)->create();

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_customers_on_dashboard()
    {
        $customers = Customer::factory()->count(5)->create(['opened_by_id' => $this->user->id]);

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas('customers');

        foreach ($customers as $customer) {
            $response->assertSee($customer->first_name . ' ' . $customer->last_name);
        }
    }

    /** @test */
    public function it_should_filter_by_active_customers()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = User::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        Financing::factory()->create();
        Financer::factory()->create();
        Term::factory()->create();
        Rates::factory()->create();
        $departmentManager->save();

        $setter = User::factory()->create([
            'role'          => 'Setter',
            'department_id' => $department->id,
        ]);

        $activeCustomers = Customer::factory()->count(3)->create([
            'is_active'    => true,
            'opened_by_id' => $setter->id,
        ]);

        $inactiveCustomers = Customer::factory()->count(3)->create([
            'is_active'    => false,
            'opened_by_id' => $setter->id,
        ]);

        $this->actingAs($setter);

        $response = $this->get('/?sort_by=is_active');

        foreach ($activeCustomers as $activeCustomer) {
            $response->assertSee($activeCustomer->first_name . ' ' . $activeCustomer->last_name);
        }

        foreach ($inactiveCustomers as $inactiveCustomer) {
            $response->assertDontSee($inactiveCustomer->first_name . ' ' . $inactiveCustomer->last_name);
        }
    }

    /** @test */
    public function it_should_filter_by_inactive_customers()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $setter = User::factory()->create([
            'role'          => 'Setter',
            'department_id' => $department->id,
        ]);
        $activeCustomers   = Customer::factory()->count(3)->create([
            'is_active'    => true,
            'opened_by_id' => $setter->id,
        ]);
        $inactiveCustomers = Customer::factory()->count(3)->create([
            'is_active'    => false,
            'opened_by_id' => $setter->id,
        ]);

        $this->actingAs($setter);

        $response = $this->get('/?sort_by=is_inactive');

        foreach ($activeCustomers as $activeCustomer) {
            $response->assertDontSee($activeCustomer->first_name . ' ' . $activeCustomer->last_name);
        }

        foreach ($inactiveCustomers as $inactiveCustomer) {
            $response->assertSee($inactiveCustomer->first_name . ' ' . $inactiveCustomer->last_name);
        }
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $this->actingAs(User::factory()->create(['role' => 'Setter']));

        $response = $this->get(route('customers.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs($departmentManager);
        $response = $this->get(route('customers.create'));

        $response->assertStatus(200)
            ->assertViewIs('customer.create');
    }

    /** @test */
    public function it_should_store_a_new_customer()
    {
        $user     = User::factory()->create();
        $userOne  = User::factory()->create(['role' => 'Setter']);
        $userTwo  = User::factory()->create(['role' => 'Sales Rep']);
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

        // $response = $this->post(route('customers.store'), $data);

        Livewire::test(Create::class, [
            'bills' => Customer::BILLS,
            'customer' => $customer
        ])->call('store')
            ->assertSee($customer->first_name);

        // $created = Customer::where('first_name', $data['first_name'])->first();

        // $livewire->assertRedirect('home');
        // $response->assertStatus(302)
        //     ->assertRedirect(route('home'));
    }

    /** @test */
    public function it_should_require_some_fields_to_store_a_new_customer()
    {
        $customer = Customer::factory()->make();

        Livewire::test(Create::class, [
            'bills' => Customer::BILLS,
            'customer' => $customer
        ])->call('store')
        ->assertHasErrors([
            'customer.first_name' => 'required',
            'customer.last_name' => 'required',
            'customer.bill' => 'required',
        ]);
    }

    /** @test */
    public function it_should_show_the_edit_form()
    {
        $customer = Customer::factory()->create();

        Livewire::test(Edit::class, [
            'customer' => $customer
        ])->assertViewIs('livewire.customer.edit');

    }

    /** @test */
    public function it_should_update_a_customer()
    {
        $customer       = Customer::factory()->create(['adders' => 30.5]);

        Livewire::test(Edit::class, [
            'customer' => $customer
        ])->set('customer.adders', 24.7)
            ->call('update');

        $this->assertDatabaseHas('customers',
            [
                'id'     => $customer->id,
                'adders' => 24.7,
            ]);
    }

    /** @test */
    public function it_should_block_updating_a_form_for_non_top_level_roles()
    {
        $this->actingAs(User::factory()->create([
            'role' => 'Setter',
            'department_id' => Department::factory()->create()
            ]));

        $customer       = Customer::factory()->create(['adders' => 30.5]);

        Livewire::test(Edit::class, [
            'customer' => $customer
        ])->set('customer.adders', 24.7)
            ->assertDontSee('Update');
    }

    /** @test */
    public function it_should_inactivate_a_customer()
    {
        $customer = Customer::factory()->create(['is_active' => true]);

        $response = $this->put(route('customers.active', $customer));

        $response->assertStatus(302);

        $this->assertDatabaseHas('customers', [
            'id'        => $customer->id,
            'is_active' => false,
        ]);
    }

    /** @test */
    public function it_should_activate_a_customer()
    {
        $customer = Customer::factory()->create(['is_active' => false]);

        $response = $this->put(route('customers.active', $customer));

        $response->assertStatus(302);

        $this->assertDatabaseHas('customers', [
            'id'        => $customer->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_should_delete_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->delete(route('customers.delete', $customer));

        $response->assertStatus(302);

        $deleted = Customer::where('id', $customer->id)->first();

        $this->assertNull($deleted);
    }

    /** @test */
    public function it_should_calculate_comission()
    {
        $customer                = new Customer();
        $customer->system_size   = 4.5;
        $customer->adders        = 300;
        $customer->epc           = 4.7;
        $customer->setter_fee    = 0.2;
        $customer->sales_rep_fee = 3.1;
        $customer->calcComission();

        $this->assertEquals($customer->sales_rep_comission, 6000.00);
    }

    /** @test */
    public function it_should_show_sales_rep_and_setter_fees()
    {
        $saleRepRate = Rates::factory()->create([
            'role' => 'Sales Rep',
            'rate' => 3,
        ]);
        $setterRate  = Rates::factory()->create([
            'role' => 'Setter',
            'rate' => 6,
        ]);

        $response = $this->get(route('customers.create'));

        $response->assertSee($saleRepRate->rate)
            ->assertSee($setterRate->rate);
    }

     /** @test */
     public function it_should_save_user_overrides_on_customer()
     {
        $financing = Financing::factory()->create();
        $financer  = Financer::factory()->create();
        $user      = User::factory()->create(['role' => 'Department Manager']);
        $regionMng = User::factory()->create(['role' => 'Region Manager']);
        $officeMng = User::factory()->create(['role' => 'Office Manager']);
        $userOne   = User::factory()->create(['role' => 'Setter']);
        $userTwo   = User::factory()->create([
            'recruiter_id'                => $userOne->id,
            'referral_override'           => 10,
            'office_manager_id'           => $officeMng->id,
            'region_manager_id'           => $regionMng->id,
            'department_manager_id'       => $user->id,
            'office_manager_override'     => 10,
            'region_manager_override'     => 20,
            'department_manager_override' => 30,
            'misc_override_one'           => 10,
            'payee_one'                   => 'payee one',
            'note_one'                    => 'note one',
            'payee_two'                   => 'payee two',
            'note_two'                    => 'note two',
        ]);

        Livewire::test(Create::class, [
            'bills' => Customer::BILLS,
        ])  ->set('customer.first_name', 'First Name')
            ->set('customer.last_name', 'Last Name')
            ->set('customer.adders', 10)
            ->set('customer.epc', 10)
            ->set('customer.financing_id', $financing->id)
            ->set('customer.financer_id', $financer->id)
            ->set('customer.bill', 'Bill')
            ->set('customer.system_size', 4)
            ->set('customer.setter_id', $userOne->id)
            ->set('customer.setter_fee', 20)
            ->set('customer.sales_rep_id', $userTwo->id)
            ->set('customer.sales_rep_fee', 20)
            ->set('customer.sales_rep_comission', 0)
            ->call('store');

        $this->assertDatabaseHas('customers', [
            'sales_rep_recruiter_id'      => $userOne->id,
            'referral_override'           => 10,
            'office_manager_id'           => $officeMng->id,
            'region_manager_id'           => $regionMng->id,
            'department_manager_id'       => $user->id,
            'office_manager_override'     => 10,
            'region_manager_override'     => 20,
            'department_manager_override' => 30,
            'misc_override_one'           => 10,
            'payee_one'                   => 'payee one',
            'note_one'                    => 'note one',
            'payee_two'                   => 'payee two',
            'note_two'                    => 'note two',
        ]);
     }
}
