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
        $this->user = factory(User::class)->create();
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
}