<?php

namespace App\Http\Livewire\Customer;

use App\Models\Financing;
use App\Models\Financer;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use App\Models\Term;
use Livewire\Component;

class Create extends Component
{

    public int $openedById;

    public int $departmentId;

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
        'customer.setter_id'           => 'required',
        'customer.setter_fee'          => 'required',
        'customer.sales_rep_id'        => 'required',
        'customer.sales_rep_fee'       => 'required',
        'customer.enium_points'        => 'nullable',
        'customer.sales_rep_comission' => 'required',
        'customer.margin'              => 'required',
        'customer.opened_by_id'        => 'required',
    ];

    public $bills;

    public function mount()
    {
        $this->customer = new Customer();
        if(user()->role != 'Admin' && user()->role != 'Owner'){
            $this->departmentId = user()->department_id;
        } else {
            $this->departmentId = Department::first()->id;
        }
    }

    public function render()
    {
        $this->customer->calcComission();
        $this->customer->calcMargin();
        return view('livewire.customer.create',[
            'departments' => Department::all(),
            'setterFee'   => $this->getSetterFee(),
            'financings'  => Financing::all(),
            'users'       => User::whereDepartmentId($this->departmentId)->get(),
            'financers'   => Financer::all(),
            'terms'       => Term::all(),
        ]);
    }

    public function store()
    {
        dd($this->customer->date_of_sale);
        $this->validate();
        // $customer                      = new Customer();
        // $customer->first_name          = $validated['first_name'];
        // $customer->last_name           = $validated['last_name'];
        // $customer->bill                = $validated['bill'];
        // $customer->financing_id        = $validated['financing_id'];
        // $customer->financer_id         = $validated['financer_id'] ?? null;
        // $customer->term_id             = $validated['term_id'] ?? null;
        // $customer->system_size         = $validated['system_size'];
        // $customer->adders              = $validated['adders'];
        // $customer->epc                 = $validated['epc'];
        // $customer->setter_id           = $validated['setter_id'];
        // $customer->setter_fee          = $validated['setter_fee'];
        // $customer->sales_rep_fee       = $validated['sales_rep_fee'];
        // $customer->sales_rep_id        = $validated['sales_rep_id'];
        // $customer->sales_rep_comission = $validated['sales_rep_comission'];
        // $customer->enium_points        = $validated['enium_points'] ?? 0;
        // $customer->opened_by_id        = $validated['opened_by_id'];


        $this->customer->save();

        alert()
            ->withTitle(__('Home Owner created!'))
            ->send();

        return redirect(route('home'));
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

        if($user) {
            $rate = Rates::whereRole($user->role);
            $rate->when($user->role == 'Sales Rep', function($query) use ($user) {
                $query->where('time', '<=', $user->installs)->orderBy('time', 'desc');
            });

            if ($rate) {
                return $user->pay;
            }

            return $rate->first()->rate;
        }

        return 0;
    }
}
