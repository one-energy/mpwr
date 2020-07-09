<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $count = 0;

    public $text = '';

    public function render()
    {
        return view('livewire.users', [
            'users' => User::query()->paginate(),
        ]);
    }

    public function add()
    {
        $this->count += 1;
    }
}
