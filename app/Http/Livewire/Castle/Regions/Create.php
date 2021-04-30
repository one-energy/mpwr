<?php

namespace App\Http\Livewire\Castle\Regions;

use Illuminate\Support\Collection;
use Livewire\Component;

class Create extends Component
{
    public Collection | array $managers;

    public function mount()
    {
        $this->managers = collect();
    }

    public function render()
    {
        return view('livewire.castle.regions.create');
    }

    public function syncManagers($managers)
    {
        $this->managers = collect($managers);
    }
}
