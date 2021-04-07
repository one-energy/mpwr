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
            'officeSelected'       => 'required|integer',
            'date'                 => 'nullable|date',
            'numbers'              => 'required|array',
            'numbers.*.doors'      => 'required|integer|min:0|gte:numbers.*.sets',
            'numbers.*.hours'      => 'required|integer|min:0|max:24',
            'numbers.*.sets'       => 'required|integer|min:0|gte:numbers.*.closes',
            'numbers.*.set_sits'   => 'required|integer|min:0',
            'numbers.*.sits'       => 'required|integer|min:0',
            'numbers.*.set_closes' => 'required|integer|min:0',
            'numbers.*.closes'     => 'required|integer|min:0',
        ];
    }
}
