<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return in_array($user->role, User::TOPLEVEL_ROLES, true);
    }

    public function show(User $user, Customer $customer)
    {
        return $user->is($customer->userSalesRep) ||
            $user->is($customer->userSetter) ||
            $user->is($customer->userOpenedBy);
    }

    public function update(User $user, Customer $customer)
    {
        return $user->is($customer->userSalesRep) || $user->is($customer->userOpenedBy);
    }
}
