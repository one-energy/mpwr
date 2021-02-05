<?php

namespace App\Http\Livewire\Customer;

use App\Financer;
use App\Financing;
use App\Models\Rates;
use App\Models\User;
use App\Term;
use Livewire\Component;

class Create extends Component
{

    public int $openedById;

    public $bills;

    public $financings;

    public $salesRepRate;

    public $setterRate;

    public $selecteFinanacing;

    public $selecteFinancer;

    public $salesRepComission;

    public function render()
    {
        $this->financings = Financing::all();
        return view('livewire.customer.create',[
            'setterFee' => $this->getSetterFee(),
            'users'     => User::all(),
            'financers' => Financer::all(),
            'terms'     => Term::all(),
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
        $this->salesRepRate = $this->getUserRate($userId);
        $this->calcComission();
    }

    public function getSetterRate($userId)
    {
        $this->setterRate = $this->getUserRate($userId);
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
        $this->salesRepComission = 5;
    }
}
