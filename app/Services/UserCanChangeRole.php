<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserCanChangeRole
{
    public function handler(User $user): array
    {
        $response = [
            'status'  => true,
            'message' => ''
        ];
        $previous = 'This user is the manager for the';

        if($offices = User::userManageOffices($user)){
            $response['status']  = false;
            $previous .= ' Offices:';
            $previous = $this->getMessage($previous, $offices);
            $response['message'] = $previous;
        }

        if($regions = User::userManageRegion($user)){
            $response['status']  = false;
            $previous .= ' Regions:';
            $previous = $this->getMessage($previous, $regions);
            $response['message'] = $previous;
        }

        if($departments = User::userManageDepartment($user)){
            $response['status']  = false;
            $previous .= ' Departments:';
            $previous = $this->getMessage($previous, $departments);
            $response['message'] = $previous;
        }

        return $response;
    }

    protected function getMessage(string $previous, Collection $content): string
    {
        $message = $previous . ' ' . $content->implode('name', ', ');
        return $message . '. Please disassociate the user from what was mentioned before continuing.';
    }
}
