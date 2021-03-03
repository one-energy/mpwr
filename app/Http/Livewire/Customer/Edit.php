<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use App\Models\Department;
use App\Models\Financer;
use App\Models\Financing;
use App\Models\Rates;
use App\Models\Term;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public Customer $customer;

    public ?User $setter;

    public int $departmentId;

    public int $grossRepComission;

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
        'customer.financer_id'         => 'nullable',
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
            $this->departmentId = Department::first()->id;
        }
    }

    public function render()
    {
        $this->customer->calcComission();
        $this->grossRepComission = $this->calculateGrossRepComission($this->customer);
        $this->salesReps = user()->getPermittedUsers()->toArray();
        $this->setters = User::whereDepartmentId($this->departmentId)->orderBy('first_name')->get()->toArray();
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
        $this->validate();

        $commission = $this->calculateCommission($this->customer);

        $this->customer->commission = $commission;
        $this->customer->save();

        alert()
            ->withTitle(__('Home Owner updated!'))
            ->send();

        return redirect(route('customers.show', $this->customer->id));
    }

    public function delete()
    {
        $this->customer->delete();

        alert()
            ->withTitle(__('Home Owner deleted!'))
            ->send();

        return redirect()->route('home');
    }

    public function setSelfGen()
    {
        $this->customer->setter_fee = 0;
    }

    public function updatedCustomerSalesRepId($salesRepId)
    {
        $this->getSalesRepRate($salesRepId);
    }

    public function updatedCustomerSetterId($setterId)
    {
        if ($setterId) {
            $this->setter = User::find($setterId);
            $this->getSetterRate($setterId);
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
        return (($customer->epc - ($customer->pay + $customer->setter_fee)) * ($customer->system_size * 1000)) - $customer->adders;
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
