<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class DailyEntry extends Component
{
    public $date;

    public $officeSelected;

    public $dateSelected;

    public $lastDateSelected;

    public $users;

    public $missingDates = [];

    public $missingOffices = [];

    public $usersLastDayEntries;
    
    protected $listeners = [
        'dailyNumbersSaved' => 'updateSum',
        'getMissingDates'   => 'getMissingDate',
    ];

    public function mount()
    {
        $this->getMissingOffices();
        $this->dateSelected     = date('Y-m-d', time());
        $this->lastDateSelected = date('Y-m-d', strtotime($this->dateSelected . '-1 day'));
        $this->setOffice(Office::first()->id);
    }

    public function getUsers($dateSelected)
    {
        return User::query()
            ->when($this->officeSelected, function(Builder $query) {
                $query->whereHas('offices', function(Builder $query) {
                    $query->whereId($this->officeSelected);
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

    public function getMissingDate($initialDate, $officeSelected)
    {
        $missingDates = [];
        $initialDate = date($initialDate);
        $today        = date('Y-m-d');
        for ($actualDate = $initialDate; $actualDate != $today; $actualDate = date('Y-m-d', strtotime($actualDate . '+1 day'))) {
            $isMissingDate = DailyNumber::whereDate('date', $actualDate)
                ->rightJoin('users', function($join) {
                    $join->on('users.id', '=', 'daily_numbers.user_id');
                })
                ->join('office_user', function($join) use ($officeSelected) {
                    $join->on('office_user.user_id', '=', 'users.id')
                         ->where('office_user.office_id', '=', $officeSelected);
                })
                ->count();
            if ($isMissingDate == 0) {
                array_push($missingDates, $actualDate);
            }
        }

        return $missingDates;
    }

    public function getMissingOffices()
    {
        $offices = Office::all();
        foreach ($offices as $key => $office) {
            $missingDates = $this->getMissingDate('Y-m-01', $office->id);

            if (count($missingDates) > 0) {
                array_push($this->missingOffices, $office);
            }
        }
    }

    public function setDate()
    {
        $this->dateSelected     = date('Y-m-d', strtotime($this->date));
        $this->lastDateSelected = date('Y-m-d', strtotime($this->dateSelected . '-1 day'));
    }

    public function setOffice($id)
    {
        $this->officeSelected = $id;
        $this->missingDates   = $this->getMissingDate('Y-m-01', $this->officeSelected);
    }

    public function sortBy()
    {
        return 'first_name';
    }

    public function render()
    {
        $this->getMissingOffices();
        $this->users               = $this->getUsers($this->dateSelected);
        $this->usersLastDayEntries = $this->getUsers($this->lastDateSelected);

        return view('livewire.number-tracker.daily-entry',[
            // 'users' => $this->users,
            'offices' => Office::all(),
        ]);
    }
}
