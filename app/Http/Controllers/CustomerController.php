<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.index');
    }

    public function create()
    {
        $bill         = Customer::BILLS;
        $financing    = Customer::FINANCINGS;
        $opened_by_id = Auth::user()->id;

        return view('customer.create', 
            [
                'bill'         => $bill,
                'financing'    => $financing,
                'opened_by_id' => $opened_by_id,
            ] 
        );
    }

    public function store()
    {
        $validated = $this->validate(
            request(),
            [
                'first_name'   => 'required',
                'last_name'    => 'required',
                'bill'         => 'required',
                'financing'    => 'required',
                'system_size'  => 'nullable',
                'pay'          => 'nullable',
                'adders'       => 'nullable',
                'gross_ppw'    => 'nullable',
                'setter'       => 'nullable',
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
        $customer->gross_ppw    = $validated['gross_ppw'];
        $customer->setter       = $validated['setter'];
        $customer->setter_fee   = $validated['setter_fee'];
        $customer->opened_by_id = $validated['opened_by_id'];

        $customer->save();

        return redirect(route('home'))->with('message', 'Home Owner created!');
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
