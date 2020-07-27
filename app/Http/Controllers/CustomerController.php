<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.index');
    }

    public function create()
    {
        $bills      = Customer::BILLS;
        $financings = Customer::FINANCINGS;
        $openedById = Auth::user()->id;
        $users      = User::get();

        return view('customer.create', 
            [
                'bills'      => $bills,
                'financings' => $financings,
                'openedById' => $openedById,
                'users'      => $users
            ] 
        );
    }

    public function store()
    {
        $validated = $this->validate(
            request(),
            [
                'first_name'   => 'required|string|min:3|max:255',
                'last_name'    => 'required|string|min:3|max:255',
                'bill'         => 'required',
                'financing'    => 'required',
                'system_size'  => 'nullable',
                'pay'          => 'nullable',
                'adders'       => 'nullable',
                'epc'          => 'nullable',
                'setter_id'    => 'nullable',
                'setter_fee'   => 'nullable',
                'opened_by_id' => 'required',
            ]
        );

        $customer               = new Customer();
        $customer->first_name   = $validated['first_name'];
        $customer->last_name    = $validated['last_name'];
        $customer->bill         = $validated['bill'];
        $customer->financing    = $validated['financing'];
        $customer->system_size  = $validated['system_size'];
        $customer->pay          = $validated['pay'];
        $customer->adders       = $validated['adders'];
        $customer->epc          = $validated['epc'];
        $customer->setter_id    = $validated['setter_id'];
        $customer->setter_fee   = $validated['setter_fee'];
        $customer->opened_by_id = $validated['opened_by_id'];

        $epc        = $customer->epc;
        $pay        = $customer->pay;
        $setterFee  = $customer->setter_fee;
        $systemSize = $customer->system_size;
        $adders     = $customer->adders;

        $commission = $this->calculateCommission($epc, $pay, $setterFee, $systemSize, $adders);

        $customer->commission = $commission;

        $customer->save();

        return redirect(route('customers.show', $customer))->with('message', 'Home Owner created!');
    }

    public function calculateCommission($epc, $pay, $setterFee, $systemSize, $adders)
    {
        return (($epc - ( $pay + $setterFee )) * $systemSize) - $adders;
    }

    public function show(int $customer)
    {
        return view('customer.show', compact('customer'));
    }

    public function update(Customer $customer)
    {
        return redirect(route('customers.index'))->with('message', 'Home Owner updated!');
    }

    public function destroy(Customer $customer)
    {
        return redirect(route('customers.index'))->with('message', 'Home Owner deleted!');
    }
}
