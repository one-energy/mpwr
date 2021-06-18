<?php

namespace Tests\Feature\Livewire\Customer;

use App\Http\Livewire\Customer\Edit;
use App\Models\Customer;
use App\Models\Department;
use App\Models\User;
use App\Enum\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_allow_user_sales_rep_delete_a_customer()
    {
        $department = Department::factory()->create();
        $john       = User::factory()->create(['role' => Role::ADMIN, 'department_id' => $department->id]);
        $customer   = Customer::factory()->create([
            'sales_rep_id' => $john->id,
        ]);

        $this->actingAs($john);

        Livewire::test(Edit::class, ['customer' => $customer])
            ->call('delete', $customer)
            ->assertHasNoErrors();

        $this->assertSoftDeleted($customer->fresh());
    }

    /** @test */
    public function it_should_allow_user_opened_by_delete_a_customer()
    {
        $department = Department::factory()->create();
        $john       = User::factory()->create(['role' => Role::ADMIN, 'department_id' => $department->id]);
        $customer   = Customer::factory()->create([
            'sales_rep_id' => $john->id,
            'opened_by_id' => $john->id,
        ]);

        $this->actingAs($john);

        Livewire::test(Edit::class, ['customer' => $customer])
            ->call('delete', $customer)
            ->assertHasNoErrors();

        $this->assertSoftDeleted($customer->fresh());
    }

    /** @test */
    public function it_should_forbidden_delete_a_customer_that_isnt_the_sales_rep_or_the_user_that_opened()
    {
        $department01 = Department::factory()->create();
        $department02 = Department::factory()->create();

        $john = User::factory()->create(['role' => Role::ADMIN, 'department_id' => $department01->id]);
        $mary = User::factory()->create(['role' => Role::ADMIN, 'department_id' => $department02->id]);

        $customer = Customer::factory()->create([
            'sales_rep_id' => $mary->id,
        ]);

        $this->actingAs($john);

        Livewire::test(Edit::class, ['customer' => $customer])
            ->call('delete', $customer)
            ->assertForbidden();

        $this->assertDatabaseHas($customer->getTable(), [
            'id' => $customer->id,
        ]);
        $this->assertNull($customer->fresh()->deleted_at);
    }
}
