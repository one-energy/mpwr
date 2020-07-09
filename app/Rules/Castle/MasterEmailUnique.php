<?php

namespace App\Rules\Castle;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class MasterEmailUnique implements Rule
{
    public function passes($attribute, $value)
    {
        return User::masters()
            ->where('id','!=', user()->id)
            ->whereEmail($value)->doesntExist();
    }

    public function message()
    {
        return __('validation.castle.masters.email.in-use');
    }
}
