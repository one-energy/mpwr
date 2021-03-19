<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
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
        $this->setOffice($this->getOfficeQuery()->first());
    }

    public function render()
    {
        $this->getMissingOffices();
        $this->users               = $this->getUsers($this->dateSelected);
        $this->usersLastDayEntries = $this->getUsers($this->lastDateSelected);
        $offices                   = $this->getOfficeQuery();

        return view('livewire.number-tracker.daily-entry',[
            'offices' => $offices->get(),
        ]);
    }

    public function getUsers($dateSelected)
    {
        $usersQuery = User::query();
        if (user()->role == "Setter" || user()->role == "Sales Rep") {
            $usersQuery->where("users.id", "=", user()->id);
        }

        return $usersQuery
            ->whereOfficeId($this->officeSelected)
            ->with(['dailyNumbers' => function($query) use ($dateSelected) {
                $query->whereDate('date', $dateSelected);
            }])
            ->orderBy('first_name')
            ->get();
    }

    public function getMissingDate($initialDate, $officeSelected)
    {
        $missingDates = [];
        $initialDate  = date($initialDate);
        $today        = date('Y-m-d');
        for ($actualDate = $initialDate; $actualDate != $today; $actualDate = date('Y-m-d', strtotime($actualDate . '+1 day'))) {
            $isMissingDate = DailyNumber::whereDate('date', $actualDate)
                ->leftJoin('users', function($join) {
                    $join->on('users.id', '=', 'daily_numbers.user_id');
                })
                ->where('users.office_id', '=', $officeSelected)
                ->count();
            if ($isMissingDate == 0) {
                array_push($missingDates, $actualDate);
            }
        }

        return $missingDates;
    }

    public function getMissingOffices()
    {
        $offices = $this->getOfficeQuery()->get();
        foreach ($offices as $office) {
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

    public function setOffice($office)
    {
        if ($office == null) {
            $this->officeSelected = 0;
        } else {
            $this->officeSelected = $office->id ?? $office['id'];
        }
        $this->missingDates   = $this->getMissingDate('Y-m-01', $this->officeSelected);
        empty($office);
    }

    //this function will be removed when OE-149 is validated
    public function save($value, $userId, $inputType)
    {
        $filteredNumbers = [
            $inputType => $value
        ];
        DailyNumber::updateOrCreate(
            [
                'user_id' => $userId,
                'date'    => $this->dateSelected,
            ],
            $filteredNumbers
        );
    }

    public function sortBy()
    {
        return 'first_name';
    }

    public function getOfficeQuery()
    {
        $query = Office::query()
            ->select("offices.*")
            ->join("regions", "region_id", "=", "regions.id");

        if (user()->role == "Admin" || user()->role == "Owner") {
            $query->where("regions.department_id", "=", 0);
        }

        if (user()->role == "Department Manager") {
            $query->where("regions.department_id", "=", user()->department_id);
        }

        if (user()->role == "Region Manager") {
            $query->where("regions.region_manager_id", "=", user()->id);
        }

        if (user()->role == "Office Manager") {
            $query->where("offices.office_manager_id", "=", user()->id);
        }

        if (user()->role == "Setter" || user()->role == "Sales Rep") {
            $query->where("offices.id", "=", user()->office_id);
        }

        return $query;
    }
}
