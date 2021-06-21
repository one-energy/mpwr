<?php

namespace Tests\Feature\Customer;

use App\Http\Livewire\Customer\Create;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Financer;
use App\Models\Financing;
use App\Models\MultiplierOfYear;
use App\Models\Rates;
use App\Models\Term;
use App\Models\User;
use App\Enum\Role;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public User $regionMng;
    public User $officeMng;
    public Financing $financing;
    public financer $financer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => Role::ADMIN]);
        $this->financing = Financing::factory()->create();
        $this->financer  = Financer::factory()->create();
        $this->regionMng = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $this->officeMng = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        Department::factory()->count(6)->create();

        MultiplierOfYear::factory()->create(['year' => now()->year]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_customers_on_dashboard()
    {
        $customers = Customer::factory()->count(5)->create(['opened_by_id' => $this->user->id]);

        $response = $this->get(route('home'))
            ->assertOk()
            ->assertViewIs('home')
            ->assertViewHas('customers');

        foreach ($customers as $customer) {
            $response->assertSee($customer->full_name);
        }
    }

    /** @test */
    public function it_should_filter_by_active_customers()
    {
        [$departmentManager, $department] = $this->createVP();

        Financing::factory()->create();
        Financer::factory()->create();
        Term::factory()->create();
        Rates::factory()->create();

        $setter = User::factory()->create([
            'role'          => 'Setter',
            'department_id' => $department->id,
        ]);

        $activeCustomers = Customer::factory()->count(3)->create([
            'is_active'    => true,
            'opened_by_id' => $setter->id,
        ]);

        $inactiveCustomers = Customer::factory()->count(3)->create([
            'is_active'    => false,
            'opened_by_id' => $setter->id,
        ]);

        $response = $this->actingAs($setter)
            ->get('/?sort_by=is_active');

        foreach ($activeCustomers as $activeCustomer) {
            $response->assertSee($activeCustomer->first_name . ' ' . $activeCustomer->last_name);
        }

        foreach ($inactiveCustomers as $inactiveCustomer) {
            $response->assertDontSee($inactiveCustomer->first_name . ' ' . $inactiveCustomer->last_name);
        }
    }

    /** @test */
    public function it_should_filter_by_inactive_customers()
    {
        [$departmentManager, $department] = $this->createVP();

        $setter            = User::factory()->create([
            'role'          => 'Setter',
            'department_id' => $department->id,
        ]);
        $activeCustomers   = Customer::factory()->count(3)->create([
            'is_active'    => true,
            'opened_by_id' => $setter->id,
        ]);
        $inactiveCustomers = Customer::factory()->count(3)->create([
            'is_active'    => false,
            'opened_by_id' => $setter->id,
        ]);

        $response = $this->actingAs($setter)
            ->get(route('home', ['sort_by' => 'is_inactive']));

        $activeCustomers->each(fn(Customer $customer) => $response->assertDontSee($customer->full_name));
        $inactiveCustomers->each(fn(Customer $customer) => $response->assertSee($customer->full_name));
    }

    /** @test */
    public function it_should_show_sales_rep_and_setter_fees()
    {
        $saleRepRate = Rates::factory()->create([
            'role' => 'Sales Rep',
            'rate' => 3,
        ]);
        $setterRate  = Rates::factory()->create([
            'role' => 'Setter',
            'rate' => 6,
        ]);

        $this->get(route('customers.create'))
            ->assertSee($saleRepRate->rate)
            ->assertSee($setterRate->rate);
    }

    /** @test */
    public function it_should_save_user_overrides_on_customer()
    {
        $user      = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $userOne   = User::factory()->create(['role' => Role::SETTER]);
        $userTwo   = User::factory()->create([
            'recruiter_id'                => $userOne->id,
            'referral_override'           => 10,
            'office_manager_id'           => $this->officeMng->id,
            'region_manager_id'           => $this->regionMng->id,
            'department_manager_id'       => $user->id,
            'office_manager_override'     => 10,
            'region_manager_override'     => 20,
            'department_manager_override' => 30,
            'misc_override_one'           => 10,
            'payee_one'                   => 'payee one',
            'note_one'                    => 'note one',
            'payee_two'                   => 'payee two',
            'note_two'                    => 'note two',
        ]);

        Livewire::test(Create::class, [
            'bills' => Customer::BILLS,
        ])->set('customer.first_name', 'First Name')
            ->set('customer.last_name', 'Last Name')
            ->set('customer.adders', 10)
            ->set('customer.epc', 10)
            ->set('customer.date_of_sale', Carbon::now())
            ->set('customer.financing_id', $this->financing->id)
            ->set('customer.financer_id', $this->financer->id)
            ->set('customer.bill', 'Bill')
            ->set('customer.system_size', 4)
            ->set('customer.setter_id', $userOne->id)
            ->set('customer.setter_fee', 20)
            ->set('customer.sales_rep_id', $userTwo->id)
            ->set('customer.sales_rep_fee', 20)
            ->set('customer.sales_rep_comission', 0)
            ->set('customer.margin', 0)
            ->call('store');

        $this->assertDatabaseHas('customers', [
            'sales_rep_recruiter_id'      => $userOne->id,
            'referral_override'           => 10,
            'office_manager_id'           => $this->officeMng->id,
            'region_manager_id'           => $this->regionMng->id,
            'department_manager_id'       => $user->id,
            'office_manager_override'     => 10,
            'region_manager_override'     => 20,
            'department_manager_override' => 30,
            'misc_override_one'           => 10,
            'payee_one'                   => 'payee one',
            'note_one'                    => 'note one',
            'payee_two'                   => 'payee two',
            'note_two'                    => 'note two',
        ]);
    }
}
