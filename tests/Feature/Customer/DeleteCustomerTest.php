<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_delete_a_customer()
    {
        $john     = User::factory()->create(['role' => 'Admin']);
        $customer = Customer::factory()->create();

        $this
            ->actingAs($john)
            ->delete(route('customers.delete', $customer))
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertNull(Customer::find($customer->id));
    }
}
