<?php

namespace App\Http\Livewire\Castle;

use App\Models\User;
use Livewire\Component;
use App\Traits\Livewire\FullTable;

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
            'users' => User::query()
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}