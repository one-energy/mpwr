<?php

namespace Tests\Feature\Customer;

use App\Enum\Role;
use App\Http\Livewire\Customer\Edit;
use App\Models\Customer;
use App\Models\CustomersStockPoint;
use App\Models\Department;
use App\Models\MultiplierOfYear;
use App\Models\StockPointsCalculationBases;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\TestCase;

class EditCustomerTest extends TestCase
{
    use DatabaseTransactions;

    public User $departmentManager;
    public User $regionManager;
    public User $officeManager;
    public User $salesRep;
    public User $setter;
    public Department $department;
    public Customer $customer;
    public Collection $stockPointsCalculations;
    public float $multiplier;
    public Collection $customers;

    protected function setUp ():void
    {
        parent::setUp();

        $this->stockPointsCalculations = StockPointsCalculationBases::factory()->count(8)
            ->state(new Sequence(
                ['id' => StockPointsCalculationBases::RECRUIT_ID],
                ['id' => StockPointsCalculationBases::SETTING_ID],
                ['id' => StockPointsCalculationBases::DEPARTMENT_ID],
                ['id' => StockPointsCalculationBases::DIVISIONAL_ID],
                ['id' => StockPointsCalculationBases::OFFICE_MANAGER_ID],
                ['id' => StockPointsCalculationBases::PERSONAL_SALES_ID],
                ['id' => StockPointsCalculationBases::POD_LEADER_TEAM_ID],
                ['id' => StockPointsCalculationBases::REGIONAL_MANAGER_ID],
            ))    
            ->create();

        $this->department = Department::factory()->create();

        $this->departmentManager = User::factory()->create([
            'role' => Role::DEPARTMENT_MANAGER,
            'department_id' => Department::factory(),
        ]);

        $this->departmentManager = User::factory()->create([
            'role' => Role::DEPARTMENT_MANAGER,
            'department_id' => $this->department->id
        ]);
        $this->regionManager     = User::factory()->create([
            'role' => Role::REGION_MANAGER,
            'department_id' => $this->department->id
        ]);
        $this->officeManager     = User::factory()->create([
            'role' => Role::OFFICE_MANAGER,
            'department_id' => $this->department->id
        ]);
        $this->setter            = User::factory()->create([
            'role' => Role::SETTER,
            'department_id' => $this->department->id
        ]);
        $this->salesRep          = User::factory()->create([
            'role' => Role::SALES_REP,
            'department_id'         => $this->department->id,
            'department_manager_id' => $this->departmentManager->id,
            'region_manager_id'     => $this->regionManager->id,
            'office_manager_id'     => $this->officeManager->id,
        ]);

        $this->multiplier = MultiplierOfYear::factory()->create(['year' => 2021])->multiplier;
        
        $this->customers = Customer::factory()
            ->count(2)
            ->create([
                'term_id'      => Term::factory()->create(),
                'sales_rep_id' => $this->salesRep->id,
                'is_active'    => true,
                'panel_sold'   => true
            ]);
        
        $this->actingAs($this->salesRep);
    }
    
    /** @test */
    public function it_should_create_personal_stock_points_when_set_as_install_customer()
    {
        Livewire::test(Edit::class,['customer' => $this->customers->first()])
            ->set('customer.panel_sold', true)
            ->call('update');
        
        
        $this->assertDatabaseHas('customers_stock_points', [
            'customer_id'         => $this->customers->first()->id,
            'stock_personal_sale' => StockPointsCalculationBases::find(StockPointsCalculationBases::PERSONAL_SALES_ID)->stock_base_point
        ]);
    }
}
