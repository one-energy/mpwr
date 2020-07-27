<?php

namespace Tests\Feature;

use App\Models\Customer;
use Tests\Feature\FeatureTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends FeatureTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(factory(User::class)->create());
    }

    /** @test */
    public function it_should_list_all_customers_on_dashboard()
    {
        $this->withoutExceptionHandling();
        $customers = factory(Customer::class, 5)->create();
        
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas('customers');

        $this->assertEquals($customers->sortBy('name')->pluck('id')->toArray(), $response->viewData('customers')->pluck('id')->toArray());
    }

    /** @test */
    public function it_should_show_the_create_form()
    {
        $response = $this->get('customers/create');

        $response->assertStatus(200)
            ->assertViewIs('customer.create');
    }

    /** @test */
    public function it_should_store_a_new_customer()
    {
        factory(Customer::class)->create();

        $response = $this->post('customers/store', [
            'first_name' => 'First Name',
            'last_name'  => 'Last Name',
            'bill'       => 'Bill',
            'financing'  => 'Financing'
        ]);

        $response->assertStatus(302)
            ->assertSessionHas('message', 'Home Owner created!');

        $this->assertDatabaseHas('customers', [
            'first_name' => 'First Name',
            'last_name'  => 'Last Name',
            'bill'       => 'Bill',
            'financing'  => 'Financing'
        ]);
    }
}
