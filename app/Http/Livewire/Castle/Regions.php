<?php

namespace App\Http\Livewire\Castle;

use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Regions extends Component
{
    use FullTable;

    public ?Region $deletingRegion;

    public string $deleteMessage = "Are you sure you want to delete this office?";

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        if (user()->role == "Department Manager") {
            $regions = Region::query()->select('regions.*')
                ->join('departments', 'regions.department_id', '=', 'departments.id')
                ->where('departments.department_manager_id', '=', user()->id);
        }
        if (user()->role == "Region Manager") {
            $regions = Region::query()->select('regions.*')
                ->where('region_manager_id', '=', user()->id);
        }
        if (user()->role == "Admin" || user()->role == "Owner") {
            $regions = Region::query();
        }

        return view('livewire.castle.regions', [
            'regions' => $regions
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }

    public function setDeletingRegion($regionId = null)
    {
        $this->deletingRegion = Region::find($regionId);
        if ($this->deletingRegion  && count($this->deletingRegion->offices)) {
            $this->deleteMessage = 'This region is NOT empty. By deleting this region you will also be deleting all other organizations or users in it. To continue, please type the name of the region below and press confirm:';
        } else {
            $this->deleteMessage = 'Are you sure you want to delete this region?';
        }
    }
}
