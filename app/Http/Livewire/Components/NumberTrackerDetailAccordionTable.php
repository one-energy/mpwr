<?php

namespace App\Http\Livewire\Components;

use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class NumberTrackerDetailAccordionTable extends Component
{
    use FullTable;

    public Collection $regions;

    public array $itsOpenRegions;

    public Carbon $startDate;

    public Carbon $endDate;

    public function mount()
    {
        $query = Region::with(['offices', 'offices.users']);

        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            $this->regions = $query->get();
        } else {
            $this->regions = $query->whereDepartmentid(user()->department_id ?? 0);
        }

        $this->addItsOpen();
    }

    public function render()
    {
        return view('livewire.components.number-tracker-detail-accordion-table');
    }

    public function sortBy()
    {
        return 'doors';
    }

    public function addItsOpen()
    {
        $this->itsOpenRegions = $this->regions->map(function ($region) {
            $region->itsOpen = false;
            $region->offices->map(function ($office) {
                return $office->itsOpen = false;
            });
            return $region;
        })->toArray();

    }

    public function collapseRegion( int $regionIndex)
    {
        $this->itsOpenRegions[$regionIndex]['itsOpen'] = !$this->itsOpenRegions[$regionIndex]['itsOpen'];
    }

    public function collapseOffice( int $regionIndex, int $officeIndex)
    {
        $this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['itsOpen'] = !$this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['itsOpen'];
    }
}
