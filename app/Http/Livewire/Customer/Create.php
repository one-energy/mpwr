<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use App\Models\Department;
use App\Models\Financer;
use App\Models\Financing;
use App\Models\Rates;
use App\Models\Term;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Create extends Component
{
    public int $openedById;

    public int $departmentId;

    public int $grossRepComission;

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
        'customer.adders'              => 'required',
        'customer.date_of_sale'        => 'required',
        'customer.epc'                 => 'required',
        'customer.financing_id'        => 'required',
        'customer.financer_id'         => 'nullable',
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

        $this->customer = new Customer();
        if (user()->role == 'Office Manager' || user()->role == 'Sales Rep' || user()->role == 'Setter') {
            $this->customer->sales_rep_id  = user()->id;
            $this->salesRep = user();
        }
        $this->customer->sales_rep_fee = $this->getUserRate(user()->id);
        $this->setSelfGen();
    }

    public function render()
    {
        $this->customer->calcComission();
        $this->customer->calcMargin();
        $this->grossRepComission = $this->calculateGrossRepComission($this->customer);
        $this->salesReps = user()->getPermittedUsers($this->departmentId)->toArray();
        $this->setters = User::whereDepartmentId($this->departmentId)
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

        $this->customer->date_of_sale = Carbon::parse($this->customer->date_of_sale);
        $this->customer->opened_by_id = user()->id;
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
        if ($this->customer->setter_id) {
            $this->getSetterRate($this->customer->setter_id);
        } else {
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
            return intval($customer->margin) * intval($customer->system_size) * 1000;
        } else {
            return 0;
        }

        return 0;
    }
}
