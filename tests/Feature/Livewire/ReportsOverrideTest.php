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

    private function makeCustomer($attr)
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
