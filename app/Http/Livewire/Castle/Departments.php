<?php

namespace App\Http\Livewire\Castle;

use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Departments extends Component
{
    use FullTable;

    public $deletingName;

    public ?Department $deletingDepartment;

    public string $deleteMessage = 'Are you sure you want to delete this department?';

    protected $rules = [
        'deletingDepartment.name' => 'nullable',
    ];

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        return view('livewire.castle.departments', [
            'departments' => Department::query()
                ->with('departmentAdmin')
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }

    public function setDeletingDepartment(?Department $department)
    {
        $this->resetValidation();
        $this->deletingDepartment = $department;
        if ($this->deletingDepartment && ($department->regions()->count() || $department->users()->count())) {
            $this->deleteMessage = 'This department is NOT empty. By deleting this department you will also be deleting all other organizations or users in it. To continue, please type the name of the department below and press confirm:';
        } else {
            $this->deleteMessage = 'Are you sure you want to delete this Department?';
        }
    }

    public function destroy()
    {
        /** @var Department $department */
        $department = $this->deletingDepartment;

        if ($department->regions()->count() || $department->users()->count()) {
            $this->validate([
                'deletingName' => 'same:deletingDepartment.name',
            ], [
                'deletingName.same' => 'The name of the department doesn\'t match',
            ]);
        }

        DB::transaction(function () use ($department) {
            $departmentManager = $department->departmentAdmin;
            $departmentManager->update(['department_id' => null]);

            $officeIds = Office::whereIn('region_id', $department->regions->pluck('id'))
                ->select('id')
                ->get();

            DailyNumber::whereHas(
                'user.office',
                fn(Builder $query) => $query->whereIn('id', $officeIds)
            )->delete();

            Office::whereIn('id', $officeIds)->delete();

            $department->regions()->delete();
            $department->users()->delete();
            $department->incentives()->delete();
            $department->delete();
        });

        $this->dispatchBrowserEvent('close-modal');
        $this->deletingDepartment = null;

        alert()
            ->withTitle(__('Department has been deleted!'))
            ->livewire($this)
            ->send();
    }
}
