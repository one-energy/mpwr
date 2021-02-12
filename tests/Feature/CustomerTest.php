<?php

namespace Tests\Feature;

use App\Http\Livewire\Castle\Rate;
use App\Http\Livewire\Customer\Create;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['role' => 'Admin']);
        factory(Department::class, 6)->create();

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_customers_on_dashboard()
    {
        $customers = factory(Customer::class, 5)->create();

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas('customers');

        foreach ($customers as $customer) {
            $response->assertSee($customer->first_name);
        }
    }

    /** @test */
    public function it_should_filter_by_active_customers()
    {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $setter            = factory(User::class)->create([
            "role" => "Setter",
            'department_id' => $department->id,
        ]);


        $activeCustomers   = factory(Customer::class, 3)->create([
            'is_active' => true,
            'opened_by_id' => $setter->id
            ]);

            $inactiveCustomers = factory(Customer::class, 3)->create([
                'is_active' => false,
                'opened_by_id' => $setter->id
                ]);

        $this->actingAs($setter);

        $response = $this->get('/?sort_by=is_active');

        foreach ($activeCustomers as $activeCustomer) {
            $response->assertSee($activeCustomer->first_name);
        }

        foreach ($inactiveCustomers as $inactiveCustomer) {
            $response->assertDontSee($inactiveCustomer->first_name);
        }
    }

    /** @test */
    public function it_should_filter_by_inactive_customers()
    {
        $activeCustomers   = factory(Customer::class, 3)->create(['is_active' => true]);
        $inactiveCustomers = factory(Customer::class, 3)->create(['is_active' => false]);

        $response = $this->get('/?sort_by=is_inactive');

        foreach ($activeCustomers as $activeCustomer) {
            $response->assertDontSee($activeCustomer->first_name);
        }

        foreach ($inactiveCustomers as $inactiveCustomer) {
            $response->assertSee($inactiveCustomer->first_name);
        }
    }

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Setter']));

        $response = $this->get(route('customers.create'));

        $response->assertStatus(403);
    }

     /** @test */
     public function it_should_show_the_create_form_for_top_level_roles()
     {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department        = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
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
        $user = factory(User::class)->create();
        $userOne = factory(User::class)->create(['role' => "Setter"]);
        $userTwo = factory(User::class)->create(['role' => "Sales Rep"]);
        $data = [
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
            "created_at"          => Carbon::now()->timestamp,
            "updated_at"          => Carbon::now()->timestamp,
            'is_active'           => true
        ];

        $response = $this->post(route('customers.store'), $data);


        $created = Customer::where('first_name', $data['first_name'])->first();

        $response->assertStatus(302)
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function it_should_require_some_fields_to_store_a_new_customer()
    {
        $data = [
            'first_name'   => '',
            'last_name'    => '',
            'bill'         => '',
            'opened_by_id' => '',
        ];

        $response = $this->post(route('customers.store'), $data);
        $response->assertSessionHasErrors(
        [
            'first_name',
            'last_name',
            'bill',
            'opened_by_id',
        ]);
    }

    /** @test */
    public function it_should_show_the_edit_form()
    {
        $customer = factory(Customer::class)->create();

        $response = $this->get('customers/'. $customer->id);

        $response->assertStatus(200)
            ->assertViewIs('customer.show');
    }

    /** @test */
    public function it_should_update_a_customer()
    {
        $customer       = factory(Customer::class)->create(['adders' => 30.5]);
        $data           = $customer->toArray();
        $updateCustomer = array_merge($data, ['adders' => 24.7]);

        $response = $this->put(route('customers.update', $customer->id), $updateCustomer);

        $response->assertStatus(302);

        $this->assertDatabaseHas('customers',
        [
            'id'  => $customer->id,
            'adders' => 24.7
        ]);
    }

    /** @test */
    public function it_should_block_updating_a_form_for_non_top_level_roles()
    {
        $this->actingAs(factory(User::class)->create(['role' => 'Setter']));

        $customer       = factory(Customer::class)->create(['adders' => 30.5]);
        $data           = $customer->toArray();
        $updateCustomer = array_merge($data, ['adders' => 24.7]);

        $response = $this->put(route('customers.update', $customer->id), $updateCustomer);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_inactivate_a_customer()
    {
        $customer = factory(Customer::class)->create(['is_active' => true]);

        $response = $this->put(route('customers.active', $customer));

        $response->assertStatus(302);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'is_active' => false
        ]);
    }

    /** @test */
    public function it_should_activate_a_customer()
    {
        $customer = factory(Customer::class)->create(['is_active' => false]);

        $response = $this->put(route('customers.active', $customer));

        $response->assertStatus(302);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'is_active' => true
        ]);
    }

    /** @test */
    public function it_should_delete_a_customer()
    {
        $customer = factory(Customer::class)->create();

        $response = $this->delete(route('customers.delete', $customer));

        $response->assertStatus(302);

        $deleted  = Customer::where('id', $customer->id)->first();

        $this->assertNull($deleted);

    }

    /** @test */
    public function it_should_calculate_comission()
    {
        $customer = new Customer();
        $customer->system_size   = 4.5;
        $customer->adders        = 300;
        $customer->epc           = 4.7;
        $customer->setter_fee    = 0.2;
        $customer->sales_rep_fee = 3.1;
        $customer->calcComission();

        $this->assertEquals($customer->sales_rep_comission, 6000.00 );
    }

     /** @test */
     public function it_should_show_sales_rep_and_setter_fees()
     {
        $saleRepRate = factory(Rates::class)->create([
             'role' => 'Sales Rep',
             'rate' => 3
             ]);
        $setterRate = factory(Rates::class)->create([
             'role' => 'Setter',
             'rate' => 6
             ]);

        $response = $this->get(route('customers.create'));

        $response->assertSee($saleRepRate->rate)
                ->assertSee($setterRate->rate);

     }
}
