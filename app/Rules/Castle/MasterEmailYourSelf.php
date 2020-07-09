<?php

namespace App\Rules\Castle;

use Illuminate\Contracts\Validation\Rule;

class MasterEmailYourSelf implements Rule
{
    public function passes($attribute, $value)
    {
        return $value != user()->email;
    }

    public function message()
    {
        return __('validation.castle.masters.email.yourself');
    }
}
