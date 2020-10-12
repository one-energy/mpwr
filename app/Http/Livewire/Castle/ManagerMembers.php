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
        $usersQuery = User::query();

        if(user()->role == "Admin" || user()->role == "Owner"){
            $users = $usersQuery;
        }else{
            $users = $usersQuery->whereDepartmentId(user()->department_id);
        }
        
        return view('livewire.castle.manager-members', [
            'users' => $users
                ->search($this->search)                
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
