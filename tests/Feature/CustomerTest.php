<?php

namespace Tests\Feature;

use App\Models\Customer;
use Tests\Feature\FeatureTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends FeatureTest
{
    use RefreshDatabase;

    /** @test */
    public function it_should_list_alll_customers_on_dashboard()
    {
        $this->withoutExceptionHandling();
        $customers = factory(Customer::class, 5)->create();
        
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('customers');
        $this->assertEquals($customers->sortBy('name')->pluck('id')->toArray(), $response->viewData('customers')->pluck('id')->toArray());
    }
}
