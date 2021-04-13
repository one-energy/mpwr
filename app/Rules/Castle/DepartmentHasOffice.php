<?php

namespace App\Rules\Castle;

use App\Models\Department;
use Illuminate\Contracts\Validation\Rule;

class DepartmentHasOffice implements Rule
{
    private ?int $departmentId;
    private string $attribute;

    public function __construct($departmentId)
    {
        $this->departmentId = $departmentId;
    }

    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;

        if (!$this->departmentId) {
            return true;
        }

        /** @var Department $department */
        $department = Department::findOrFail($this->departmentId);

        return $department->offices()->where('offices.id', $value)->exists();
    }

    public function message()
    {
        return trans('validation.exists', ['attribute' => $this->attribute]);
    }
}
