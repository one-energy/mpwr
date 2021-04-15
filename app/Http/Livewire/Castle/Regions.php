<?php

namespace App\Http\Livewire\Castle;

use App\Models\Department;
use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Regions extends Component
{
    use FullTable;

    public $deletingName;

    public ?Region $deletingRegion;

    public string $deleteMessage = 'Are you sure you want to delete this office?';

    protected $rules = [
        'deletingRegion.name' => 'nullable',
    ];

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        $regions = Region::query()
            ->when(user()->role == 'Department Manager', function (Builder $query) {
                $departmentIds = Department::query()
                    ->where('department_manager_id', '=', user()->id)
                    ->pluck('id');

                $query->whereIn('department_id', $departmentIds);
            })
            ->when(user()->role == 'Region Manager', function (Builder $query) {
                $query->where('region_manager_id', '=', user()->id);
            })
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.castle.regions', [
            'regions' => $regions,
        ]);
    }

    public function setDeletingRegion(?Region $region)
    {
        $this->resetValidation();
        $this->deletingRegion       = $region;
        $this->deletingRegion->name = trim($region->name);
        if ($this->deletingRegion && $region->offices()->count()) {
            $this->deleteMessage = 'This region is NOT empty. By deleting this region you will also be deleting all other organizations or users in it. To continue, please type the name of the region below and press confirm:';
        } else {
            $this->deleteMessage = 'Are you sure you want to delete this region?';
        }
    }

    public function destroy()
    {
        $region = $this->deletingRegion;

        if ($region->offices()->count()) {
            $this->validate(
                ['deletingName' => 'same:deletingRegion.name'],
                ['deletingName.same' => "The name of the region doesn't match"]
            );
        }

        DB::transaction(function () use ($region) {
            $region->delete();
            $region
                ->trainingPageSections()
                ->whereNotNull('parent_id')
                ->where('department_folder', false)
                ->delete();
        });

        $this->dispatchBrowserEvent('close-modal');
        $this->deletingRegion = null;

        alert()
            ->withTitle(__('Region has been deleted!'))
            ->livewire($this)
            ->send();
    }
}
