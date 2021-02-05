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

    public $selecteFinanacing;

    public $selecteFinancer;

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
}
