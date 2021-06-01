<?php

namespace Tests\Feature\Customer;

use App\Http\Livewire\Customer\Edit;
use App\Models\Customer;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_show_the_edit_form()
    {
        $john       = User::factory()->create(['role' => 'Department Manager']);
        $department = Department::factory()->create();
        $customer   = Customer::factory()->create();

        $john->update(['department_id' => $department->id]);

        $this->actingAs($john);

        Livewire::test(Edit::class, ['customer' => $customer])
            ->assertViewIs('livewire.customer.edit');
    }

    /** @test */
    public function it_should_update_a_customer()
    {
        $this->markTestSkipped('must be revisited.');

        $john       = User::factory()->create(['role' => 'Department Manager']);
        $department = Department::factory()->create();
        $customer   = Customer::factory()->create(['adders' => 30.5]);

        $john->update(['department_id' => $department->id]);

        $this->actingAs($john);

        Livewire::test(Edit::class, ['customer' => $customer])
            ->set('customer.adders', 24.7)
            ->call('update');

        $this->assertDatabaseHas('customers', [
            'id'     => $customer->id,
            'adders' => 24.7,
        ]);
    }

    /** @test */
    public function it_should_block_updating_a_form_for_non_top_level_roles()
    {
        $this->actingAs(User::factory()->create([
            'role'          => 'Setter',
            'department_id' => Department::factory()->create(),
        ]));

        $customer = Customer::factory()->create(['adders' => 30.5]);

        Livewire::test(Edit::class, ['customer' => $customer])
            ->set('customer.adders', 24.7)
            ->assertDontSee('Update');
    }

    /** @test */
    public function it_should_inactivate_a_customer()
    {
        $john     = User::factory()->create(['role' => 'Admin']);
        $customer = Customer::factory()->create([
            'is_active'    => true,
            'sales_rep_id' => $john->id,
        ]);

        $this
            ->actingAs($john)
            ->put(route('customers.active', $customer))
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('customers', [
            'id'        => $customer->id,
            'is_active' => false,
        ]);
    }

    /** @test */
    public function it_should_activate_a_customer()
    {
        $john     = User::factory()->create(['role' => 'Admin']);
        $customer = Customer::factory()->create([
            'is_active'    => false,
            'sales_rep_id' => $john->id,
        ]);

        $this
            ->actingAs($john)
            ->put(route('customers.active', $customer))
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('customers', [
            'id'        => $customer->id,
            'is_active' => true,
        ]);
    }
}
