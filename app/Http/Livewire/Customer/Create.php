<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use App\Models\Department;
use App\Models\Financer;
use App\Models\Financing;
use App\Models\Rates;
use App\Models\Term;
use App\Models\User;
use App\Models\UserCustomersEniumPoints;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public int $openedById;

    public int $departmentId;

    public float $grossRepComission;

    public int $stockPoints = 250;

    public $searchSalesRep;

    public User $salesRep;

    public User $setter;

    public array $salesReps;

    public array $setters;

    public Customer $customer;

    protected $rules = [
        'customer.first_name'          => ['required', 'string', 'max:255'],
        'customer.last_name'           => ['required', 'string', 'max:255'],
        'customer.system_size'         => 'required',
        'customer.bill'                => 'required',
        'customer.adders'              => ['required', 'min:0'],
        'customer.date_of_sale'        => 'required',
        'customer.epc'                 => 'required',
        'customer.financing_id'        => 'required',
        'customer.financer_id'         => 'required_if:customer.financing_id,1',
        'customer.term_id'             => 'nullable',
        'customer.setter_id'           => 'nullable',
        'customer.setter_fee'          => 'required',
        'customer.sales_rep_id'        => 'required',
        'customer.sales_rep_fee'       => 'required',
        'customer.enium_points'        => 'nullable',
        'customer.sales_rep_comission' => 'required',
        'customer.margin'              => 'required',
        'stockPoints'                  => 'required',
        'grossRepComission'            => 'required',
    ];

    public $bills;

    public function mount()
    {
        if (user()->role != 'Admin' && user()->role != 'Owner') {
            $this->departmentId = user()->department_id;
        } else {
            $this->departmentId = Department::first()->id;
        }

        $this->customer         = new Customer();
        $this->customer->adders = 0;
        if ((user()->role == 'Office Manager' || user()->role == 'Sales Rep' || user()->role == 'Setter') && user()->office_id != null) {
            $this->customer->sales_rep_id  = user()->id;
            $this->customer->sales_rep_fee = $this->getUserRate(user()->id);
            $this->salesRep                = user();
        }
        $this->setSelfGen();
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

        return view('livewire.customer.create', [
            'departments' => Department::all(),
            'setterFee'   => $this->getSetterFee(),
            'financings'  => Financing::all(),
            'users'       => User::whereDepartmentId($this->departmentId)->orderBy('first_name')->get(),
            'financers'   => Financer::all(),
            'terms'       => Term::all(),
        ]);
    }

    public function updatedCustomerFinancingId()
    {
        if ($this->customer->financing_id != 1) {
            $this->customer->financer_id  = null;
            $this->customer->term_id      = null;
            $this->customer->enium_points = null;
        }
    }

    public function store()
    {

        $this->validate();
        $salesRep = User::find($this->customer->sales_rep_id);
        
        $this->customer->date_of_sale                = Carbon::parse($this->customer->date_of_sale);
        $this->customer->opened_by_id                = user()->id;
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

        $this->customer->save();

        alert()
            ->withTitle(__('Home Owner created!'))
            ->send();

        return redirect(route('home'));
    }

    public function updatedCustomerSalesRepId($salesRepId)
    {
        $this->getSalesRepRate($salesRepId);
        $this->salesRep = User::whereId($salesRepId)->first();
    }

    public function updatedCustomerSetterId()
    {
        if (!$this->customer->setter_id) {
            $this->setSelfGen();
        }
    }

    public function setSelfGen()
    {
        $this->customer->setter_fee = 0;
    }

    public function getSetterFee()
    {
        return Rates::whereRole('Setter')->first();
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
        $this->customer->setter_fee = $this->getUserRate($userId);
    }

    public function getUserRate($userId)
    {
        $user = User::whereId($userId)->first();

        if ($user) {
            $rate = Rates::whereRole($user->role);
            $rate->when($user->role == 'Sales Rep', function ($query) use ($user) {
                $query->where('time', '<=', $user->installs)->orderBy('time', 'desc');
            });

            if ($rate) {
                return $user->pay;
            }

            return $rate->first()->rate;
        }

        return 0;
    }

    public function calculateGrossRepComission(Customer $customer)
    {
        if ( $customer->margin >= 0 && $customer->system_size >= 0 ) {  
            return round((float) $customer->margin * (float) $customer->system_size * Customer::K_WATTS, 2);
        }
        
        return 0;
    }
}
