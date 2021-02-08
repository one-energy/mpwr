<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use App\Models\Financer;
use App\Models\Financing;
use App\Models\Rates;
use App\Models\Term;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public Customer $customer;

    protected $rules = [
        'customer.first_name'          => ['required', 'string', 'max:255'],
        'customer.last_name'           => ['required', 'string', 'max:255'],
        'customer.system_size'         => 'required',
        'customer.bill'                => 'required',
        'customer.adders'              => 'required',
        'customer.epc'                 => 'required',
        'customer.financing_id'        => 'required',
        'customer.financer_id'         => 'nullable',
        'customer.term_id'             => 'nullable',
        'customer.setter_id'           => 'required',
        'customer.setter_fee'          => 'required',
        'customer.sales_rep_id'        => 'required',
        'customer.sales_rep_fee'       => 'required',
        'customer.enium_points'        => 'required',
        'customer.sales_rep_comission' => 'required',
    ];

    public function render()
    {
        $this->customer->calcComission();
        return view('livewire.customer.edit', [
            'setterFee'  => $this->getSetterFee(),
            'users'      => User::all(),
            'bills'      => Customer::BILLS,
            'financings' => Financing::all(),
            'financers'  => Financer::all(),
            'terms'      => Term::all(),
        ]);
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
