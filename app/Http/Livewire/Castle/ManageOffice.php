<?php

namespace App\Http\Livewire\Castle;

use App\Models\Office;
use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class ManageOffice extends Component
{
    use FullTable;
    
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
        $officeQuery = Office::query();

        if (user()->role == "Admin" || user()->role == "Owner") {
            $office = $officeQuery;
        } else {
            $office = $officeQuery->whereDepartmentId(user()->department_id);
        }
        
        return view('livewire.castle.manage-office', [
            'offices' => $office
                ->search($this->search)                
                ->orderBy('name')
                ->get(),
        ]);
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
}
