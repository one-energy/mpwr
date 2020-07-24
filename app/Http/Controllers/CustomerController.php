<?php

namespace App\Http\Controllers;

use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.index');
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store()
    {
        return redirect(route('users.index'))->with('message', 'Home Owner created!');
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
