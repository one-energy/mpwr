<?php

namespace App\Http\Livewire\Customer;

use App\Models\Financing;
use App\Models\Financer;
use App\Models\Customer;
use App\Models\Rates;
use App\Models\User;
use App\Models\Term;
use Livewire\Component;

class Create extends Component
{

    public int $openedById;

    public Customer $customer;

    protected $rules = [
        'customer.first_name'          => ['required', 'string', 'max:255'],
        'customer.last_name'           => ['required', 'string', 'max:255'],
        'customer.system_size'         => 'required',
        'customer.bill'                => 'required',
        'customer.adders'              => 'required',
        'customer.epc'                 => 'required',
        'customer.financing_id'        => 'required',
        'customer.financer_id'         => 'required',
        'customer.term_id'             => 'required',
        'customer.setter_id'           => 'required',
        'customer.setter_fee'          => 'required',
        'customer.sales_rep_id'        => 'required',
        'customer.sales_rep_fee'       => 'required',
        'customer.enium_points'        => 'required',
        'customer.sales_rep_comission' => 'required',
    ];

    public $bills;

    public function mount()
    {
        $this->customer = new Customer();
    }

    public function render()
    {
        return view('livewire.customer.create',[
            'setterFee'  => $this->getSetterFee(),
            'financings' => Financing::all(),
            'users'      => User::all(),
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

        $this->calcComission();
    }

    public function getSetterRate($userId)
    {
        $this->customer->setter_fee = $this->getUserRate($userId);
        $this->calcComission();
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

    public function calcComission()
    {
        if($this->customer->epc && $this->customer->sales_rep_fee && $this->customer->setter_fee && $this->customer->system_size && $this->customer->adders) {
            $this->customer->sales_rep_comission = (($this->customer->epc - $this->customer->sales_rep_fee - $this->customer->setter_fee) * ($this->customer->system_size * 1000)) - $this->customer->adders;
        }
    }
}
