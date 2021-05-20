<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use App\Models\Department;
use App\Models\Financer;
use App\Models\Financing;
use App\Models\Rates;
use App\Models\StockPointsCalculationBases;
use App\Models\Term;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public Customer $customer;

    public ?User $setter;

    public int $departmentId;

    public bool $installed;

    public int $grossRepComission;

    public bool $isSelfGen;

    public int $stockPoints = 250;

    public array $salesReps;

    public array $setters;

    protected $rules = [
        'customer.first_name'          => ['required', 'string', 'max:255'],
        'customer.last_name'           => ['required', 'string', 'max:255'],
        'customer.system_size'         => 'required',
        'customer.bill'                => 'required',
        'customer.adders'              => 'required',
        'customer.date_of_sale'        => 'required',
        'customer.epc'                 => 'required',
        'customer.financing_id'        => 'required',
        'customer.financer_id'         => 'required_if:customer.financing_id,1',
        'customer.term_id'             => 'nullable',
        'customer.setter_id'           => 'nullable',
        'customer.setter_fee'          => 'required',
        'customer.sales_rep_id'        => 'required',
        'customer.sales_rep_fee'       => 'required',
        'customer.panel_sold'          => 'nullable',
        'customer.enium_points'        => 'nullable',
        'customer.sales_rep_comission' => 'required',
        'customer.margin'              => 'required',
        'stockPoints'                  => 'required',
        'grossRepComission'            => 'required',
    ];

    public function mount(Customer $customer)
    {
        $this->setSelfGen();
        $this->setter = User::find($customer->setter_id);
        if (user()->role != 'Admin' && user()->role != 'Owner') {
            $this->departmentId = user()->department_id;
        } else {
            $this->departmentId = $customer->userSalesRep->department_id;
        }
    }

    public function render()
    {
        $this->customer->calcComission();
        $this->customer->calcMargin();
        $this->grossRepComission = $this->calculateGrossRepComission($this->customer);
        $this->salesReps         = user()->getPermittedUsers($this->departmentId)->toArray();
        $this->setters           = User::whereDepartmentId($this->departmentId)
                                ->where('id', '!=', user()->id)
                                ->orderBy('first_name')->get()->toArray();

        return view('livewire.customer.edit', [
            'departments' => Department::all(),
            'setterFee'   => $this->getSetterFee(),
            'users'       => User::whereDepartmentId($this->departmentId)->orderBy('first_name')->get(),
            'bills'       => Customer::BILLS,
            'financings'  => Financing::all(),
            'financers'   => Financer::all(),
            'terms'       => Term::all(),
        ]);
    }

    public function update()
    {

        $salesRep   = User::find($this->customer->sales_rep_id);
        $commission = $this->calculateCommission($this->customer);

        $this->validate();

        $this->customer->commission                  = $commission;
        $this->customer->sales_rep_recruiter_id      = $salesRep->recruiter_id;
        $this->customer->referral_override           = $salesRep->referral_override;
        $this->customer->office_manager_id           = $salesRep->office_manager_id;
        $this->customer->region_manager_id           = $salesRep->region_manager_id;
        $this->customer->department_manager_id       = $salesRep->department_manager_id;
        $this->customer->office_manager_override     = $salesRep->office_manager_override;
        $this->customer->region_manager_override     = $salesRep->region_manager_override;
        $this->customer->department_manager_override = $salesRep->department_manager_override;
        $this->customer->misc_override_one           = $salesRep->misc_override_one;
        $this->customer->payee_one                   = $salesRep->payee_one;
        $this->customer->note_one                    = $salesRep->note_one;
        $this->customer->misc_override_two           = $salesRep->misc_override_two;
        $this->customer->payee_two                   = $salesRep->payee_two;
        $this->customer->note_two                    = $salesRep->note_two;
        $this->customer->financing_id = $this->customer->financing_id != "" ? $this->customer->financing_id : null;
        $this->customer->financer_id = $this->customer->financer_id != "" ? $this->customer->financer_id : null;
        $this->customer->term_id = $this->customer->term_id != "" ? $this->customer->term_id : null;

        DB::transaction(function () {
            $this->customer->save();
            if ($this->customer->term_id) {
                $this->createStockPoint();
                if (!$this->customer->userEniumPoint()->exists() && $this->customer->panel_sold) {
                    $this->customer->userEniumPoint()->create([
                        'user_sales_rep_id' => $this->customer->sales_rep_id,
                        'points'            => $this->customer->term->amount > 0 ? round($this->customer->epc/$this->customer->term->amount) : 0,
                        'set_date'          => Carbon::now(),
                        'expiration_date'   => Carbon::now()->addYear()
                    ]);
                }
            }
        });

        alert()
            ->withTitle(__('Home Owner updated!'))
            ->send();

        return redirect(route('customers.show', $this->customer->id));
    }

    public function delete()
    {
        DB::transaction(function () {
            $this->customer->stockPoint()->delete();
            $this->customer->delete();
        });

        alert()
            ->withTitle(__('Home Owner deleted!'))
            ->send();

        return redirect()->route('home');
    }

    public function createStockPoint () 
    {   
        if (!$this->customer->stockPoint()->exists() && $this->customer->panel_sold) {
            $this->customer->stockPoint()->create([
                'stock_recruiter'       => StockPointsCalculationBases::find(StockPointsCalculationBases::RECRUIT_ID)->stock_base_point,
                'stock_setting'         => StockPointsCalculationBases::find(StockPointsCalculationBases::SETTING_ID)->stock_base_point,
                'stock_personal_sale'   => StockPointsCalculationBases::find(StockPointsCalculationBases::PERSONAL_SALES_ID)->stock_base_point,
                'stock_pod_leader_team' => StockPointsCalculationBases::find(StockPointsCalculationBases::POD_LEADER_TEAM_ID)->stock_base_point,
                'stock_manager'         => StockPointsCalculationBases::find(StockPointsCalculationBases::OFFICE_MANAGER_ID)->stock_base_point,
                'stock_divisional'      => StockPointsCalculationBases::find(StockPointsCalculationBases::DIVISIONAL_ID)->stock_base_point,
                'stock_regional'        => StockPointsCalculationBases::find(StockPointsCalculationBases::REGIONAL_MANAGER_ID)->stock_base_point,
                'stock_department'      => StockPointsCalculationBases::find(StockPointsCalculationBases::DEPARTMENT_ID)->stock_base_point
            ]);
        }
    }

    public function setSelfGen()
    {
        $this->customer->setter_fee = 0;
        $this->isSelfGen            = true;
    }

    public function updatedCustomerFinancingId()
    {
        if ($this->customer->financing_id != 1) {
            $this->customer->financer_id  = null;
            $this->customer->term_id      = null;
            $this->customer->enium_points = null;
        }
    }

    public function updatedCustomerSalesRepId($salesRepId)
    {
        $this->getSalesRepRate($salesRepId);
    }

    public function updatedCustomerSetterId($setterId)
    {
        if ($setterId) {
            $this->isSelfGen = false;
        } else {
            $this->setSelfGen();
        }
    }

    public function getSetterFee()
    {
        return Rates::whereRole('Setter')->first();
    }

    public function calculateCommission($customer)
    {
        return (
                ((float)$customer->epc - ((float)$customer->pay + (float)$customer->setter_fee)) *
                ((float)$customer->system_size * 1000)
            ) - (float)$customer->adders;
    }

    public function getSalesRepFee()
    {
        return Rates::whereRole('Sales Rep')->orderBy('rate', 'desc')->first();
    }

    public function getSalesRepRate($userId)
    {
        $this->customer->sales_rep_fee = $this->getUserRate($userId);
    }

    public function getSetterRate($userId)
    {
        if ($userId) {
            $this->customer->setter_fee = $this->getUserRate($userId);
        } else {
            $this->customer->setter_fee = 0;
        }
    }

    public function getUserRate($userId)
    {
        $user = User::whereId($userId)->first();

        $rate = Rates::whereRole($user->role);
        $rate->when($user->role == 'Sales Rep', function ($query) use ($user) {
            $query->where('time', '<=', $user->installs)->orderBy('time', 'desc');
        });

        if ($rate) {
            return $user->pay;
        }

        return $rate->first()->rate;
    }

    public function calculateGrossRepComission(Customer $customer)
    {
        if ($customer->margin && $customer->system_size) {
            return $customer->margin * $customer->system_size * 1000;
        }

        return 0;
    }
}
