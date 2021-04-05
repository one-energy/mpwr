<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Castle\Departments;
use App\Http\Livewire\Reports\ReportsOverview;
use App\Models\Customer;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportsOverrideTest extends TestCase
{
    use DatabaseTransactions;

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
        $this->regionManager = User::factory()->create([
            'role' => 'Region Manager',
            'department_id' => $this->department->id
        ]);
        $this->officeManager = User::factory()->create([
            'role' => 'Office Manager',
            'department_id' => $this->department->id
        ]);
        $this->salesRep = User::factory()->create([
            'role' => 'Sales_Rep',
            'department_id' => $this->department->id
        ]);
        $this->setter = User::factory()->create([
            'role' => 'Setter',
            'department_id' => $this->department->id
        ]);

        $this->customer = Customer::factory()->create([
            'setter_id' => $this->setter,
            'sales_rep_id' => $this->salesRep->id,
            'department_manager_id' => $this->departmentManager->id,
            'region_manager_id' => $this->regionManager->id,
            'office_manager_id' => $this->officeManager->id,
            'panel_sold' => false,
            'is_active' => true,
        ]);
        $this->customerTwo = Customer::factory()->create(

        );
    }

    /** @test */
    public function it_should_open_commission_reports()
    {
        $response = $this->get('/reports');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_should_see_canceled_customers()
    {

    $customer = Customer::factory()->create([
        'setter_id' => $this->setter,
        'sales_rep_id' => $this->salesRep->id,
        'department_manager_id' => $this->departmentManager->id,
        'region_manager_id' => $this->regionManager->id,
        'office_manager_id' => $this->officeManager->id,
        'panel_sold' => false,
        'is_active' => false,
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
        Livewire::test(ReportsOverview::class)
        ->set('departmentId', $this->department->id)
        ->assertSee($this->customer->first_name);
    }

    /** @test */
    public function it_should_see_instaled_customers()
    {
        $customer = $this->makeCustomer([
            'is_active'  => true,
            'panel_sold' => true
        ]);

        Livewire::test(ReportsOverview::class)
        ->set('departmentId', $this->department->id)
        ->set('selectedStatus', 'installed')
        ->assertSee($customer->first_name);
    }

     /** @test */
     public function it_should_calculate_total_commission()
     {

         Livewire::test(ReportsOverview::class)
            ->set('departmentId', $this->department->id)
            ->assertSee($this->customer->first_name);
     }

     private function makeCustomer($attr)
     {
        return Customer::factory()->create(array_merge(
            [
                'setter_id' => $this->setter,
                'sales_rep_id' => $this->salesRep->id,
                'department_manager_id' => $this->departmentManager->id,
                'region_manager_id' => $this->regionManager->id,
                'office_manager_id' => $this->officeManager->id,
                'panel_sold' => true,
                'is_active' => true,
            ],
            $attr
        ));
     }
}
