<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use Carbon\Carbon;
use Livewire\Component;

class NumberTrackerDetail extends Component
{

    public $period = 'd';

    public $numbersTracked = [];

    public $date;

    public $dateSelected;

    public function mount()
    {
        $this->dateSelected = date('Y-m-d', time());
        
    }

    public function render()
    {
        $this->numbersTracked = $this->getTrackerNumbers();
        $showOptions          = [
            'Daily Total', 
            'Weekly Total',
            'Monthly Total',
            'Statistics',
        ];
        return view('livewire.number-tracker.number-tracker-detail',['showOptions' => $showOptions]);
    }

    public function setPeriod($p)
    {
        $this->period = $p;
       
    }

    public function setDate()
    {
        $this->dateSelected = date('Y-m-d', strtotime($this->date));
    }

    public function getTrackerNumbers()
    {
            $query = DailyNumber::query()
            ->leftJoin('users', function($join) {
                $join->on('users.id', '=', 'daily_numbers.id');
            });
            
            if($this->period == 'd'){
                $query->whereDate('date', $this->dateSelected);            
            }elseif($this->period == 'w'){
                $query->whereBetween('date', [Carbon::createFromFormat('Y-m-d', $this->dateSelected)->startOfWeek(), Carbon::createFromFormat('Y-m-d', $this->dateSelected)->endOfWeek()]);
            }else{
                $query->whereMonth('date', '=', Carbon::createFromFormat('Y-m-d', $this->dateSelected)->month);
            }
            return $query->orderBy('doors', 'desc')
            ->take(5)
            ->get();         
    }
}
