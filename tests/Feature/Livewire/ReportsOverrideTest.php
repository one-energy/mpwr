<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Reports\ReportsOverview;
use App\Models\Customer;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportsOverrideTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $departmentManager;

    private User $regionManager;

    private User $officeManager;

    private User $salesRep;

    private User $setter;

    private Customer $customer;

    private Department $department;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $this->actingAs($this->admin);

        $this->department = Department::factory()->create();

        $this->departmentManager = User::factory()->create([
            'role' => 'Department Manager',
        ]);
        $this->regionManager     = User::factory()->create([
            'role'          => 'Region Manager',
            'department_id' => $this->department->id,
        ]);
        $this->officeManager     = User::factory()->create([
            'role'          => 'Office Manager',
            'department_id' => $this->department->id,
        ]);
        $this->salesRep          = User::factory()->create([
            'role'          => 'Sales Rep',
            'department_id' => $this->department->id,
        ]);
        $this->setter            = User::factory()->create([
            'role'          => 'Setter',
            'department_id' => $this->department->id,
        ]);

        $this->customer = $this->makeCustomer([
            'system_size'                 => 10,
            'setter_fee'                  => 10,
            'sales_rep_fee'               => 20,
            'department_manager_override' => 10,
            'region_manager_override'     => 20,
            'office_manager_override'     => 30,
        ]);
    }

    /** @test */
    public function it_should_open_commission_reports()
    {
        $this->get(route('reports.index'))
            ->assertOk();
    }

    /** @test */
    public function it_should_see_canceled_customers()
    {
        $customer = $this->makeCustomer([
            'panel_sold' => false,
            'is_active'  => false,
        ]);

        Livewire::test(ReportsOverview::class)
            ->set('departmentId', $this->department->id)
            ->set('selectedStatus', 'canceled')
            ->assertDontSee($this->customer->first_name)
            ->assertSee($customer->first_name);
    }

    /** @test */
    public function it_should_see_pending_customers()
    {
        $customer = $this->makeCustomer([
            'panel_sold' => false,
            'is_active'  => true,
        ]);

        Livewire::test(ReportsOverview::class)
            ->set('departmentId', $this->department->id)
            ->assertSee($customer->first_name);
    }

    /** @test */
    public function it_should_see_installed_customers()
    {
        $customer = $this->makeCustomer([
            'is_active'  => true,
            'panel_sold' => true,
        ]);

        Livewire::test(ReportsOverview::class)
            ->set('departmentId', $this->department->id)
            ->set('selectedStatus', 'installed')
            ->assertSee($customer->first_name);
    }

    /** @test */
    public function it_should_calculate_total_commission()
    {
        $this->actingAs($this->setter);

        $component = Livewire::test(ReportsOverview::class)
            ->set('selectedStatus', 'installed')
            ->call('getUserTotalCommission');

        $this->assertSame(100000.0, $component->payload['effects']['returns']['getUserTotalCommission']);
    }

    /** @test */
    public function it_should_calculate_sales_rep_total_commission()
    {
        $this->actingAs($this->salesRep);

        $component = Livewire::test(ReportsOverview::class)
            ->set('selectedStatus', 'installed')
            ->call('getUserTotalCommission');

        $this->assertSame(200000.0, $component->payload['effects']['returns']['getUserTotalCommission']);
    }

    /** @test */
    public function it_should_calculate_office_manager_total_commission()
    {
        $this->actingAs($this->officeManager);

        $component = Livewire::test(ReportsOverview::class)
            ->set('selectedStatus', 'installed')
            ->call('getUserTotalCommission');

        $this->assertSame(300000.0, $component->payload['effects']['returns']['getUserTotalCommission']);
    }

    /** @test */
    public function it_should_calculate_region_manager_total_commission()
    {
        $this->actingAs($this->regionManager);

        $component = Livewire::test(ReportsOverview::class)
            ->set('selectedStatus', 'installed')
            ->call('getUserTotalCommission');

        $this->assertSame(200000.0, $component->payload['effects']['returns']['getUserTotalCommission']);
    }

    /** @test */
    public function it_should_calculate_department_manager_total_commission()
    {
        $this->actingAs($this->departmentManager);

        $component = Livewire::test(ReportsOverview::class)
            ->set('selectedStatus', 'installed')
            ->call('getUserTotalCommission');

        $this->assertSame(100000.0, $component->payload['effects']['returns']['getUserTotalCommission']);
    }

    /** @test */
    public function it_should_calculate_all_department_total_commission()
    {
        $this->actingAs($this->admin);

        $component = Livewire::test(ReportsOverview::class)
            ->set('selectedStatus', 'installed')
            ->set('departmentId', $this->department->id)
            ->call('getUserTotalCommission');

        $this->assertSame(900000.0, $component->payload['effects']['returns']['getUserTotalCommission']);
    }

    /** @test */
    public function it_should_be_possible_see_paid_column_if_user_has_admin_role()
    {
        $this->actingAs($this->admin);

        Livewire::test(ReportsOverview::class)
            ->assertSee('Paid')
            ->assertHasNoErrors();

        $this->actingAs($this->setter);

        Livewire::test(ReportsOverview::class)
            ->assertDontSee('Paid')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_be_possible_mark_a_pending_customer_as_paid()
    {
        $this->actingAs($this->admin);

        $customer = $this->makeCustomer([
            'panel_sold' => false,
            'is_active'  => true,
            'paid_date'  => null
        ]);

        $this->assertNull($customer->paid_date);

        Livewire::test(ReportsOverview::class)
            ->assertSee('Paid')
            ->assertSee($customer->first_name)
            ->call('paid', $customer->id)
            ->assertHasNoErrors();

        $customer->refresh();

        $this->assertTrue($customer->panel_sold);
        $this->assertNotNull($customer->paid_date);
        $this->assertDatabaseHas($customer->getTable(), [
            'id'         => $customer->id,
            'panel_sold' => true
        ]);
    }

    /** @test */
    public function it_should_be_possible_to_unmark_a_paid_customer()
    {
        $this->actingAs($this->admin);

        $customer = $this->makeCustomer([
            'panel_sold' => true,
            'is_active'  => true,
            'paid_date'  => now()
        ]);

        $this->assertNotNull($customer->paid_date);

        Livewire::test(ReportsOverview::class, ['selectedStatus' => 'installed'])
            ->assertSee('Paid')
            ->assertSee($customer->first_name)
            ->call('paid', $customer->id)
            ->assertHasNoErrors();

        $customer->refresh();

        $this->assertFalse($customer->panel_sold);
        $this->assertNull($customer->paid_date);
        $this->assertDatabaseHas($customer->getTable(), [
            'id'         => $customer->id,
            'panel_sold' => false
        ]);
    }

    /** @test */
    public function it_should_forbidden_if_mark_as_paid_if_the_user_is_not_admin()
    {
        $this->actingAs($this->setter);

        $customer = $this->makeCustomer([
            'panel_sold'   => false,
            'is_active'    => true,
            'paid_date'    => null,
            'opened_by_id' => $this->regionManager->id
        ]);

        $this->assertFalse($customer->panel_sold);
        $this->assertNull($customer->paid_date);

        Livewire::test(ReportsOverview::class)
            ->assertDontSee('Paid')
            ->assertSee($customer->first_name)
            ->call('paid', $customer->id)
            ->assertForbidden();

        $customer->refresh();
        $this->assertFalse($customer->panel_sold);
        $this->assertNull($customer->paid_date);
    }

    private function makeCustomer($attr): Customer
    {
        return Customer::factory()->create(array_merge([
            'setter_id'             => $this->setter,
            'sales_rep_id'          => $this->salesRep->id,
            'department_manager_id' => $this->departmentManager->id,
            'region_manager_id'     => $this->regionManager->id,
            'office_manager_id'     => $this->officeManager->id,
            'panel_sold'            => true,
            'is_active'             => true,
        ], $attr));
    }
}
