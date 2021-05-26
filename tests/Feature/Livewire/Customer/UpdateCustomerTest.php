<?php


namespace Tests\Feature\Livewire\Customer;

use App\Http\Livewire\Customer\Edit;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_update_customer()
    {
        $john     = User::factory()->create(['role' => 'Admin']);
        $customer = Customer::factory()->create([
            'sales_rep_id' => $john->id
        ]);

        $this->actingAs($john);

        Livewire::test(Edit::class, ['customer' => $customer])
            ->call('update')
            ->assertOk();
    }

    /** @test */
    public function it_should_prevent_update_a_customer_that_not_belongs_to_the_authenticated_user()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Admin']);

        $customer = Customer::factory()->create([
            'sales_rep_id' => $mary->id
        ]);

        $this->actingAs($john);

        Livewire::test(Edit::class, ['customer' => $customer])
            ->call('update')
            ->assertForbidden();
    }
}
