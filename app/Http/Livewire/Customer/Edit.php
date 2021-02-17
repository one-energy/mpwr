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

    public int $departmentId;

    protected $rules = [
        'customer.first_name'          => ['required', 'string', 'max:255'],
        'customer.last_name'           => ['required', 'string', 'max:255'],
        'customer.system_size'         => 'required',
        'customer.bill'                => 'required',
        'customer.adders'              => 'required',
        'customer.epc'                 => 'required',
        'customer.financing_id'        => 'required',
        'customer.financer_id'         => 'nullable',
        'customer.panel_sold'          => 'required',
        'customer.term_id'             => 'nullable',
        'customer.setter_id'           => 'required',
        'customer.setter_fee'          => 'required',
        'customer.sales_rep_id'        => 'required',
        'customer.sales_rep_fee'       => 'required',
        'customer.enium_points'        => 'required',
        'customer.sales_rep_comission' => 'required',
    ];

    public function mount()
    {
        if(user()->role != 'Admin' && user()->role != 'Owner'){
            $this->departmentId = user()->department_id;
        } else {
            $this->departmentId = Department::first()->id;
        }
    }

    public function render()
    {
        $this->customer->calcComission();
        return view('livewire.customer.edit', [
            'departments' => Department::all(),
            'setterFee'  => $this->getSetterFee(),
            'users'      => User::whereDepartmentId($this->departmentId)->get(),
            'bills'      => Customer::BILLS,
            'financings' => Financing::all(),
            'financers'  => Financer::all(),
            'terms'      => Term::all(),
        ]);
    }

    public function update()
    {
        $this->validate();

        if ($this->customer->panel_sold != $this->customer->panel_sold) {
            $user = User::whereId($this->customer->sales_rep_id)->first();

            if ($this->customer->panel_sold == 1) {
                $user->installs++;
                $user->kw_achived += $this->customer->system_size;
            }

            if ($this->customer->panel_sold == 0) {
                $user->installs--;
                $user->kw_achived -= $this->customer->system_size;
            }

            $user->save();
        }

        $commission = $this->calculateCommission($this->customer);

        $this->customer->commission = $commission;

        $this->customer->save();

        dd('test');
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

    public function getSetterFee()
    {
        return Rates::whereRole('Setter')->first();
    }

    public function calculateCommission($customer)
    {
        return (($customer->epc - ( $customer->pay + $customer->setter_fee )) * ($customer->system_size * 1000)) - $customer->adders;
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

        $rate = Rates::whereRole($user->role);
        $rate->when($user->role == 'Sales Rep', function($query) use ($user) {
            $query->where('time', '<=', $user->installs)->orderBy('time', 'desc');
        });

        if ($rate) {
            return $user->pay;
        }

        return $rate->first()->rate;
    }

}
