<?php

namespace App\Http\Livewire\Reports;

use Livewire\Component;

class ReportsOverview extends Component
{
    public $search = "";

    public function render()
    {
        return view('livewire.reports.reports-overview');
    }
}
