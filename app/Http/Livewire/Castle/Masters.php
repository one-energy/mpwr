<?php

namespace App\Http\Livewire\Castle;

use App\Models\User;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Masters extends Component
{
    use FullTable;

    public function sortBy()
    {
        return 'first_name';
    }

    public function render()
    {
        return view('livewire.castle.masters', [
            'masters' => User::masters()
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
