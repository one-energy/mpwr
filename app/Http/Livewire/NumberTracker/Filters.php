<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Filters extends Component
{
    public $date = '';

    public $regionSelected = '';

    public $dateSelected = '';

    public function setDate()
    {
        $this->dateSelected = $this->date;
    }

    public function setRegion($id)
    {
        $this->regionSelected = $id;
    }

    public function sortBy()
    {
        return 'first_name';
    }

    public function render()
    {
        return view('livewire.number-tracker.filters',[
            'users' => User::query()
                ->when($this->regionSelected !== '', function(Builder $query) {
                    $query->whereHas('regions', function(Builder $query) {
                        $query->whereId($this->regionSelected);
                    })
                    ->addSelect(['role' => DB::table('region_user')
                        ->whereRaw('user_id = users.id')
                        ->where('region_id', $this->regionSelected)
                        ->select(['role'])
                        ->limit(1),
                    ]);
                })
                ->orderBy($this->sortBy())
                ->get(),
            'regions' => Region::all(),
        ]);
    }
}
