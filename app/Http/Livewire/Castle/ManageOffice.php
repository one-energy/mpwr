<?php

namespace App\Http\Livewire\Castle;

use App\Models\Office;
use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Collection;
use Livewire\Component;

class ManageOffice extends Component
{
    use FullTable;
    
    public Collection $offices;

    public $region;

    public function sortBy()
    {
        return 'name';
    }

    public function mount($region)
    {
        $this->region = $region;
    }

    public function render()
    {
        $this->offices = Office::query()
            ->with('region')
            ->when(user()->notHaveRoles(['Admin', 'Owner']), function($query) {
                $query->where('regions.department_id', '=', user()->department_id);
            })
            ->search($this->search)                
            ->orderBy('offices.name')
            ->get();

        return view('livewire.castle.manage-office');
    }

    public function addOfficeToRegion($office)
    {
        $changeOffice = Office::whereId($office)->first();

        $changeOffice['region_id'] = $this->region->id;

        $changeOffice->update();
    }

    public function removeOfficeRegion($office)
    {
        $changeOffice = Office::whereId($office)->first();

        $changeOffice['region_id'] = Region::whereDepartmentId($changeOffice->region->department_id)->first()->id;

        $changeOffice->update();
    }

    public function existsOfficesOnRegion()
    {
        return $this->offices->contains(fn ($office) => $office->region_id == $this->region->id);
    }
}
