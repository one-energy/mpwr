<?php

namespace App\Http\Livewire\Castle;

use App\Models\Office;
use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Offices extends Component
{
    use FullTable;

    public $deletingName;

    public ?Office $deletingOffice;

    public string $deleteMessage = 'Are you sure you want to delete this office?';

    protected $rules = [
        'deletingOffice.name' => 'nullable',
    ];

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        $offices = Office::query()
            ->when(user()->role == 'Region Manager', function (Builder $query) {
                $query->whereHas('region', function (Builder $query) {
                    $query->where('region_manager_id', '=', user()->id);
                });
            })
            ->when(user()->role == 'Department Manager', function (Builder $query) {
                $regionIds = Region::query()
                    ->where('department_id', '=', user()->department_id)
                    ->pluck('id');

                $query->whereIn('region_id', $regionIds);
            })
            ->when(user()->role == 'Office Manager', function (Builder $query) {
                $query->where('office_manager_id', '=', user()->id);
            })
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.castle.offices', [
            'offices' => $offices,
        ]);
    }

    public function setDeletingOffice(?Office $office)
    {
        $this->resetValidation();
        $this->deletingOffice = $office;
        if ($this->deletingOffice && $office->users()->count()) {
            $this->deleteMessage = 'This office is NOT empty. By deleting this office you will also be deleting all other organizations or users in it. To continue, please type the name of the office below and press confirm:';
        } else {
            $this->deleteMessage = 'Are you sure you want to delete this office?';
        }
    }

    public function destroy()
    {
        $office = $this->deletingOffice;

        if ($office->users()->count()) {
            $this->validate(
                ['deletingName' => 'same:deletingOffice.name'],
                ['deletingName.same' => "The name of the office doesn't match"]
            );
        }

        $office->delete();

        $this->dispatchBrowserEvent('close-modal');
        $this->deletingOffice = null;

        alert()
            ->withTitle(__('Office has been deleted!'))
            ->livewire($this)
            ->send();
    }
}
