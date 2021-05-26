<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Rates;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        return redirect()->route('home');
    }

    public function create()
    {
        $this->authorize('create', Customer::class);

        return view('customer.create', [
            'bills'       => Customer::BILLS,
            'financings'  => Customer::FINANCINGS,
            'openedById'  => user()->id,
            'users'       => User::get(),
            'setterFee'   => $this->getSetterFee()->rate ?? 0,
            'salesRepFee' => $this->getSalesRepFee()->rate ?? 0,
        ]);
    }

    public function calculateCommission($customer)
    {
        return (($customer->epc - ($customer->pay + $customer->setter_fee)) * ($customer->system_size * 1000)) - $customer->adders;
    }

    public function show(Customer $customer)
    {
        $this->authorize('update', $customer);

        return view('customer.show', [
            'customer' => $customer,
            'users'    => User::get(),
        ]);
    }

    public function active(Customer $customer)
    {
        $this->authorize('update', $customer);

        $customer->update(['is_active' => !$customer->is_active]);

        $title = $customer->is_active ? 'Home Owner set as active!' : 'Home Owner set as canceled!';

        alert()
            ->withTitle(__($title))
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
}
