<?php

namespace App\Http\Livewire\Castle;

use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\Region;
use App\Role\Role;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
            ->when(user()->hasRole(Role::REGION_MANAGER), function (Builder $query) {
                $query->whereHas('region', function (Builder $query) {
                    $query->whereIn('id', user()->managedRegions->pluck('id'));
                });
            })
            ->when(user()->hasRole(Role::DEPARTMENT_MANAGER), function (Builder $query) {
                $regionIds = Region::query()
                    ->where('department_id', '=', user()->department_id)
                    ->pluck('id');

                $query->whereIn('region_id', $regionIds);
            })
            ->when(user()->hasRole(Role::OFFICE_MANAGER), function (Builder $query) {
                $query->whereIn('id', user()->managedOffices->pluck('id'));
            })
            ->with(['managers', 'region.department'])
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

        DB::transaction(function () use ($office) {
            DailyNumber::whereHas(
                'user.office',
                fn(Builder $query) => $query->whereIn('id', [$office->id])
            )->delete();

            $office->managers()->detach(
                $office->managers->pluck('id')->toArray()
            );

            $office->managers()->update(['users.office_id' => null]);

            $office->users()->delete();
            $office->delete();
        });

        $this->dispatchBrowserEvent('close-modal');
        $this->deletingOffice = null;

        alert()
            ->withTitle(__('Office has been deleted!'))
            ->livewire($this)
            ->send();
    }

    public function openManagersListModal(Office $office)
    {
        $this->dispatchBrowserEvent('on-show-managers', [
            'managers' => $office->managers->take(4),
            'quantity' => $office->managers()->count(),
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
