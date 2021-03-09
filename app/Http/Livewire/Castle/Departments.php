<?php

namespace App\Http\Livewire\Castle;

use App\Models\Department;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Departments extends Component
{
    use FullTable;

    public ?Department $deletingDepartment;

    public string $deleteMessage = "Are you sure you want to delete this department?";

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        return view('livewire.castle.departments', [
            'departments' => Department::join('users', 'users.id', '=', 'departments.department_manager_id')
                ->select('departments.*', 'users.first_name', 'users.last_name')
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }

    public function setDeletingDepartment($departmentId = null)
    {
        $this->deletingDepartment = Department::find($departmentId);
        if ($this->deletingDepartment  && count($this->deletingDepartment->regions)) {
            $this->deleteMessage = 'This department is NOT empty. By deleting this department you will also be deleting all other organizations or users in it. To continue, please type the name of the department below and press confirm:';
        } else {
            $this->deleteMessage = 'Are you sure you want to delete this Department?';
        }
    }
}
