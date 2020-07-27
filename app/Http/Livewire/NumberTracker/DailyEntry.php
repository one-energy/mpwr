<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class DailyEntry extends Component
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
        $this->dateSelected = ($this->dateSelected == "") ? date('Y-m-d', time()) : $this->dateSelected;

        return view('livewire.number-tracker.daily-entry',[
            'users' => User::query()
                ->when($this->regionSelected, function(Builder $query) {
                    $query->whereHas('regions', function(Builder $query) {
                        $query->whereId($this->regionSelected);
                    });
                })
                ->leftJoin('daily_numbers', function($join) {
                    $join->on('daily_numbers.user_id', '=', 'users.id')
                        ->where('daily_numbers.date', '=', $this->dateSelected);
                })
                ->orderBy($this->sortBy())
                ->select(
                    'users.*', 
                    'daily_numbers.doors', 
                    'daily_numbers.hours', 
                    'daily_numbers.sets', 
                    'daily_numbers.sits', 
                    'daily_numbers.set_closes', 
                    'daily_numbers.closes'
                )
                ->get(),
            'regions' => Region::all(),
        ]);
    }
}
