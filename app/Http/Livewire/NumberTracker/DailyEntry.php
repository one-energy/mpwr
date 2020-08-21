<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Region;
use App\Models\User;
use App\Models\DailyNumber;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class DailyEntry extends Component
{
    public $date;

    public $regionSelected;

    public $dateSelected;

    public $lastDateSelected;

    public $users;

    public $missingDates = [];

    public $usersLastDayEntries;
    
    protected $listeners = [
        'dailyNumbersSaved' => 'updateSum',
        'getMissingDates' => 'getMissingDate'
    ];

    public function mount()
    {
        $this->dateSelected     = date('Y-m-d', time());
        $this->lastDateSelected = date('Y-m-d', strtotime($this->dateSelected . '-1 day'));
        $this->setRegion(Region::first()->id);
    }

    public function getUsers($dateSelected)
    {
        return User::query()
            ->when($this->regionSelected, function(Builder $query) {
                $query->whereHas('regions', function(Builder $query) {
                    $query->whereId($this->regionSelected);
                });
            })
            ->leftJoin('daily_numbers', function($join) use ($dateSelected) {
                $join->on('daily_numbers.user_id', '=', 'users.id')
                    ->where('daily_numbers.date', '=', $dateSelected);
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
            ->get();
    }

    public function getMissingDate($initialDate, $regionSelected)
    {
        $today = date('Y-m-d');
        for($actualDate = $initialDate; $actualDate != $today; $actualDate = date('Y-m-d', strtotime($actualDate . '+1 day'))){
            $isMissingDate = DailyNumber::whereDate('date', $actualDate)
                ->rightJoin('users', function($join)  {
                    $join->on('users.id', '=', 'daily_numbers.user_id');
                })
                ->join('region_user', function($join) use ($regionSelected) {
                    $join->on('region_user.user_id', '=', 'users.id')
                         ->where('region_user.region_id', '=', $regionSelected);
                })
                ->select('region_user.region_id')
                ->count();
            if($isMissingDate == 0){
                array_push($this->missingDates, $actualDate);
            }
        }
        // dd($this->missingDates);
    }

    public function setDate()
    {
        $this->dateSelected     = date('Y-m-d', strtotime($this->date));
        $this->lastDateSelected = date('Y-m-d', strtotime($this->dateSelected . '-1 day'));
    }

    public function setRegion($id)
    {
        $this->regionSelected = $id;
        $this->getMissingDate('2020-08-01', $this->regionSelected);
    }

    public function sortBy()
    {
        return 'first_name';
    }

    public function render()
    {
        $this->users               = $this->getUsers($this->dateSelected);
        $this->usersLastDayEntries = $this->getUsers($this->lastDateSelected);
        
        return view('livewire.number-tracker.daily-entry',[
            // 'users' => $this->users,
            'regions' => Region::all(),
        ]);
    }
}
