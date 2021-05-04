<?php

namespace App\Http\Livewire\Castle;

use App\Models\DailyNumber;
use App\Models\Region;
use App\Models\SectionFile;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Models\User;
use App\Role\Role;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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
            ->when(user()->hasRole(Role::DEPARTMENT_MANAGER), function (Builder $query) {
                $query->whereIn('department_id', user()->managedDepartments->pluck('id'));
            })
            ->when(user()->hasRole(Role::REGION_MANAGER), function (Builder $query) {
                $query->whereIn('id', user()->managedRegions->pluck('id'));
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
            $parentSection = TrainingPageSection::query()
                ->where(function (Builder $query) use ($region) {
                    $query->where('department_id', $region->department_id)
                        ->whereNull('parent_id');
                })
                ->first();

            $trainingPageSectionsIds = $region->trainingPageSections()
                ->select('id')
                ->pluck('id')
                ->toArray();

            TrainingPageContent::query()
                ->whereIn('training_page_section_id', $trainingPageSectionsIds)
                ->update(['training_page_section_id' => $parentSection?->id]);

            SectionFile::query()
                ->whereIn('training_page_section_id', $trainingPageSectionsIds)
                ->update(['training_page_section_id' => $parentSection?->id]);

            $region->managers()->detach($region->managers->pluck('id')->toArray());

            $region
                ->trainingPageSections()
                ->whereNotNull('parent_id')
                ->where('department_folder', false)
                ->delete();

            DailyNumber::whereHas(
                'user.office',
                fn (Builder $query) => $query->whereIn('id', $region->offices->pluck('id'))
            )->delete();

            User::whereIn('office_id', $region->offices->pluck('id'))->delete();

            $region->offices()->delete();

            $region->delete();
        });

        $this->dispatchBrowserEvent('close-modal');
        $this->deletingRegion = null;

        alert()
            ->withTitle(__('Region has been deleted!'))
            ->livewire($this)
            ->send();
    }

    public function openManagersListModal(Region $region)
    {
        $this->dispatchBrowserEvent('on-show-managers', [
            'managers' => $region->managers->take(4),
            'quantity' => $region->managers()->count(),
        ]);
    }

    public function getManagersName(Collection $managers)
    {
        return $managers
            ->take(3)
            ->pluck('full_name')
            ->join(', ');
    }
}
