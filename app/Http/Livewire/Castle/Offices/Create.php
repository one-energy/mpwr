<?php

namespace App\Http\Livewire\Castle\Offices;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public Collection | array $managers;

    private string $uuid;

    public function mount()
    {
        $this->uuid     = (string)Str::uuid();
        $this->managers = collect();
    }

    public function render()
    {
        return view('livewire.castle.offices.create');
    }

    public function getWireKeyProperty()
    {
        $managers = collect($this->managers);

        if ($managers->isEmpty()) {
            return sprintf('%s-%s-%s', user()->id, $this->uuid, $managers->count());
        }

        return sprintf('%s-%s-%s', user()->id, $managers->first()['id'], $managers->count());
    }

    public function syncManagers($managers)
    {
        $this->managers = collect($managers);
    }
}