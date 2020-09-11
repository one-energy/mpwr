<?php

namespace App\Http\Livewire\Castle;

use App\Models\User;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Users extends Component
{
    use FullTable;

    public function sortBy()
    {
        return 'first_name';
    }

    public function render()
    {
        return view('livewire.castle.users', [
            'users' => User::with('office')
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}