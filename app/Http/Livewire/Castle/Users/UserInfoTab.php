<?php

namespace App\Http\Livewire\Castle\Users;

use App\Models\User;
use Livewire\Component;

class UserInfoTab extends Component
{
    public $openedTab = 'userInfo';

    public function render()
    {
        return view('livewire.castle.users.user-info-tab');
    }

    public function changeTab($selectedTab)
    {
        $this->openedTab = $selectedTab;
    }

    public function userRole($userRole)
    {
        $roles =  User::ROLES;
        $roleTitle = '';
        foreach ($roles as $role) {
            if ($role['name'] == $userRole) {
                $roleTitle = $role['title'];
            }
        }
        return $roleTitle;
    }
}
