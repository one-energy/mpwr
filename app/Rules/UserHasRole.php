<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UserHasRole implements Rule
{
    private string $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function passes($attribute, $value)
    {
        /** @var User $user */
        $user = User::find($value);

        if (!$user) {
            return false;
        }

        return $user->hasRole($this->role);
    }

    public function message()
    {
        return 'The selected user does not have the role of ' . $this->role;
    }
}
