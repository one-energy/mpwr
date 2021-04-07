<?php

namespace App\Http\Requests\NumberTracking;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreNumberTrackingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'date'    => $this->date ? new Carbon($this->date) : now(),
        ]);
    }

    public function rules()
    {
        return [
            'officeSelected' => 'required|integer',
            'date'           => 'nullable|date',
            'numbers'        => 'required|array',
        ];
    }
}
