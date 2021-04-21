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

    public Collection $ids;

    public array $itsOpenRegions;

    public $selected;

    public Carbon $startDate;

    public Carbon $endDate;

    public function mount()
    {
        $query = Region::with(['offices', 'offices.users']);
        $this->ids = collect([]);

        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            $this->regions = $query->get();
        } else {
            $this->regions = $query->whereDepartmentId(user()->department_id ?? 0)->get();
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
            $region->itsOpen  = false;
            $region->selected = true;
            $region->offices->map(function ($office) {
                $office->itsOpen = false;
                $office->selected = true;
                return $office->users->map(function ($user) {
                    return $user->selected = true;
                });
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

    public function selectRegion($regionIndex)
    {
        $this->itsOpenRegions[$regionIndex]['selected'] = !$this->itsOpenRegions[$regionIndex]['selected'];
    }

    public function selectOffice($regionIndex, $officeIndex)
    {
        $this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['selected'] = !$this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['selected'];
    }

    public function selectUser($regionIndex, $officeIndex, $userIndex)
    {
        $this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['users'][$userIndex]['selected'] = !$this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['users'][$userIndex]['selected'];
        $this->selected = $this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['users'][$userIndex]['selected'];
    }

    public function teste($teste)
    {
        if($this->ids->contains($teste)) {
            $this->ids = $this->ids->filter(function ($id) use ($teste) {
                return $id != $teste;
            });
        } else {
            $this->ids = $this->ids->merge($teste);
        }
    }
}
