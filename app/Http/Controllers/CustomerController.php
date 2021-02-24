<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Rates;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $this->authorize('viewList', Customer::class);

        return redirect()->route('home');
    }

    public function create()
    {
        $this->authorize('create', Customer::class);

        $bills       = Customer::BILLS;
        $financings  = Customer::FINANCINGS;
        $openedById  = Auth::user()->id;
        $users       = User::get();
        $setterFee   = $this->getSetterFee();
        $salesRepFee = $this->getSalesRepFee();

        return view('customer.create', [
                'bills'       => $bills,
                'financings'  => $financings,
                'openedById'  => $openedById,
                'users'       => $users,
                'setterFee'   => $setterFee->rate ?? 0,
                'salesRepFee' => $salesRepFee->rate ?? 0,
        ]);
    }

    public function delete(Customer $customer)
    {
        $customer->delete();

        alert()
            ->withTitle(__('Home Owner deleted!'))
            ->send();

        return redirect()->route('home');
    }

    public function calculateCommission($customer)
    {
        return (($customer->epc - ($customer->pay + $customer->setter_fee)) * ($customer->system_size * 1000)) - $customer->adders;
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', Customer::class);

        $users = User::get();

        return view('customer.show', [
            'customer' => $customer,
            'users'    => $users,
        ]);
    }

    public function active(Customer $customer)
    {
        $this->authorize('update', Customer::class);

        $customer->is_active = !$customer->is_active;
        $customer->save();

        if ($customer->is_active == true) {
            alert()
                ->withTitle(__('Home Owner set as active!'))
                ->send();
        } else {
            alert()
                ->withTitle(__('Home Owner set as canceled!'))
                ->send();
        }

        return redirect(route('home'));
    }

    public function destroy(Customer $customer)
    {
        $this->authorize('delete', Customer::class);

        return redirect(route('customers.index'))->with('message', 'Home Owner deleted!');
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
