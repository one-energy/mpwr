<?php

namespace Tests\Feature\Customer;

use App\Enum\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $this->actingAs(User::factory()->create(['role' => Role::SETTER]))
            ->get(route('customers.create'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $department        = Department::factory()->create(['department_manager_id' => $departmentManager->id]);

        $departmentManager->update(['department_id' => $department->id]);

        $this->actingAs($departmentManager)
            ->get(route('customers.create'))
            ->assertOk()
            ->assertViewIs('customer.create');
    }
}
