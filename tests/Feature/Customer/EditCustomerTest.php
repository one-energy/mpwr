<?php

namespace Tests\Feature\Customer;

use App\Enum\Role;
use App\Http\Livewire\Customer\Edit;
use App\Models\Customer;
use App\Models\CustomersStockPoint;
use App\Models\Department;
use App\Models\Financer;
use App\Models\Financing;
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
        
        $this->customer = Customer::factory()
            ->create([
                'term_id'       => Term::factory()->create()->id,
                'financer_id'   => Financer::factory()->create()->id,
                'financing_id'  => Financing::factory()->create(['id' => 1])->id,
                'enium_points'  => 10,
                'setter_id'     => $this->setter->id,
                'sales_rep_id'  => $this->salesRep->id,
                'sales_rep_fee' => $this->salesRep->pay,
                'is_active'     => true,
                'panel_sold'    => true
            ]);
        
        $this->actingAs($this->salesRep);
    }
    
    /** @test */
    public function it_should_create_stock_points_when_set_as_install_customer()
    {
        Livewire::test(Edit::class,['customer' => $this->customer])
            ->set('customer.panel_sold', true)
            ->call('update');
            
        $this->assertDatabaseHas('customers_stock_points', [
            'customer_id'         => $this->customer->id,
            'stock_personal_sale' => (int) StockPointsCalculationBases::find(StockPointsCalculationBases::PERSONAL_SALES_ID)->stock_base_point,
            'stock_setting'       => (int) StockPointsCalculationBases::find(StockPointsCalculationBases::SETTING_ID)->stock_base_point,
            'stock_manager'       => (int) StockPointsCalculationBases::find(StockPointsCalculationBases::OFFICE_MANAGER_ID)->stock_base_point,
            'stock_recruiter'     => (int) StockPointsCalculationBases::find(StockPointsCalculationBases::RECRUIT_ID)->stock_base_point,
            'stock_regional'      => (int) StockPointsCalculationBases::find(StockPointsCalculationBases::REGIONAL_MANAGER_ID)->stock_base_point,
            'stock_department'    => (int) StockPointsCalculationBases::find(StockPointsCalculationBases::DEPARTMENT_ID)->stock_base_point,
        ]);
    }

    /** @test */
    public function it_should_set_setter_as_self_gen()
    {
        Livewire::test(Edit::class,['customer' => $this->customer])
            ->assertSet('isSelfGen', false) 
            ->set('customer.setter_id', 0)
            ->assertSet('isSelfGen', true)
            ->assertSet('customer.setter_fee', 0);
    }

    /** @test */
    public function it_should_set_financer_term_and_enium_point_as_null_when_financing_is_not_purchase()
    {
        Livewire::test(Edit::class,['customer' => $this->customer])
            ->set('customer.financing_id', 2)
            ->assertSet('customer.enium_points', null)
            ->assertSet('customer.term_id', null)
            ->assertSet('customer.financer_id', null);
    }

    /** @test */
    public function it_should_show_financer_required_error_when_financing_is_purchase()
    {
        Livewire::test(Edit::class,['customer' => $this->customer])
        ->set('customer.financer_id', null)
        ->call('update')
        ->assertHasErrors(['customer.financer_id']);
    }

    /** @test */
    public function it_should_update_sales_rep_rate_when_change_sales_rep()
    {
        $jhon = User::factory()->create([
            'role'                  => Role::SALES_REP,
            'department_id'         => $this->department->id,
            'department_manager_id' => $this->departmentManager->id,
            'region_manager_id'     => $this->regionManager->id,
            'office_manager_id'     => $this->officeManager->id,
        ]);

        Livewire::test(Edit::class,['customer' => $this->customer])
        ->assertSet('customer.sales_rep_fee', $this->salesRep->pay)
        ->set('customer.sales_rep_id', $jhon->id)
        ->assertSet('customer.sales_rep_fee', $jhon->pay);
    }
}
