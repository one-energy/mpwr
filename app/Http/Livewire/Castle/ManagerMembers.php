<?php

namespace App\Http\Livewire\Castle;

use App\Models\User;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class ManagerMembers extends Component
{
    use FullTable;
    
    public $office;

    public function sortBy()
    {
        return 'first_name';
    }

    public function mount($office)
    {
        $this->office = $office;
    }

    public function render()
    {
        return view('livewire.castle.manager-members', [
            'users' => User::search($this->search)                
                ->orderBy('first_name')
                ->get(),
        ]);
    }

    public function addUserToOffice($user)
    {
        $changeUser = User::whereId($user['id'])->first();

        $changeUser['office_id'] = $this->office->id;

        $changeUser->update();
    }

    public function removeUserOffice($user)
    {
        $changeUser = User::whereId($user['id'])->first();

        $changeUser['office_id'] = null;

        $changeUser->update();
    }
}
