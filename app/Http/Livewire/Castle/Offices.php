<?php

namespace App\Http\Livewire\Castle;

use App\Models\Office;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Offices extends Component
{
    use FullTable;

    public ?Office $deletingOffice;

    public string $deleteMessage = "Are you sure you want to delete this office?";

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        $officesQuery = Office::query()->select('offices.*');

        if (user()->role == "Region Manager") {
            $officesQuery->join('regions', 'offices.region_id', '=', 'regions.id')
                ->where('regions.region_manager_id', '=', user()->id);
        }

        if (user()->role == "Department Manager") {
            $officesQuery->join('regions', 'offices.region_id', '=', 'regions.id')
                ->where('regions.department_id', '=', user()->department_id);
        }

        if (user()->role == "Office Manager") {
            $officesQuery->where('office_manager_id', '=', user()->id);
        }

        if (user()->role == "Owner" || user()->role == "Admin") {
            $officesQuery;
        }

        return view('livewire.castle.offices', [
            'offices' => $officesQuery
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }

    public function setDeletingOffice($officeId = null)
    {
        $this->deletingOffice = Office::find($officeId);
        // dd($officeId);
        if ($this->deletingOffice  && count($this->deletingOffice->users)) {
            $this->deleteMessage = 'This office is NOT empty. By deleting this office you will also be deleting all other organizations or users in it. To continue, please type the name of the office below and press confirm:';
        } else {
            $this->deleteMessage = 'Are you sure you want to delete this office?';
        }
    }
}
