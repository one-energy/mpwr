<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['role' => 'Admin']);

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
        $activeCustomers   = factory(Customer::class, 3)->create(['is_active' => 1]);
        $inactiveCustomers = factory(Customer::class, 3)->create(['is_active' => 0]);

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
        $activeCustomers   = factory(Customer::class, 3)->create(['is_active' => 1]);
        $inactiveCustomers = factory(Customer::class, 3)->create(['is_active' => 0]);

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

        $response = $this->get('customers/create');

        $response->assertStatus(403);
    }

     /** @test */
     public function it_should_show_the_create_form_for_top_level_roles()
     {
        $response = $this->get('customers/create');
        
        $response->assertStatus(200)
            ->assertViewIs('customer.create');
     }

    /** @test */
    public function it_should_store_a_new_customer()
    {
        $data = [
            'first_name' => 'First Name',
            'last_name'  => 'Last Name',
            'bill'       => 'Bill',
            'financing'  => 'Financing'
        ];

        $response = $this->post(route('customers.store'), $data);

        $created = Customer::where('first_name', $data['first_name'])->first();

        $response->assertStatus(302)
            ->assertSessionHas('message', 'Home Owner created!')
            ->assertRedirect(route('customers.show', ['customer' => $created]));

        $this->assertDatabaseHas('customers', $created->toArray());
    }

    /** @test */
    public function it_should_require_some_fields_to_store_a_new_customer()
    {
        $data = [
            'first_name'   => '',
            'last_name'    => '',
            'bill'         => '',
            'financing'    => '',
            'opened_by_id' => ''
        ];

        $response = $this->post(route('customers.store'), $data);
        $response->assertSessionHasErrors(
        [
            'first_name',
            'last_name',
            'bill',
            'financing',
            'opened_by_id'
        ]);
    }
}