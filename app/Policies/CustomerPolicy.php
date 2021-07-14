<?php

namespace App\Policies;

use App\Enum\Role;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $roles = collect(Role::getValues())->except(Role::SETTER)->toArray();

        return in_array($user->role, $roles, true);
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
