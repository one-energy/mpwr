<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class UserCanChangeRole
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handler(User $user): array
    {
        $response = [
            'status'  => true,
            'message' => ''
        ];
        $previous = 'This user is the manager for the';

        if($offices = $this->userRepository->userManageOffices($user)){
            $response['status']  = false;
            $previous .= ' Offices:';
            $previous = $this->getMessage($previous, $offices);
            $response['message'] = $previous;
        }

        if($regions = $this->userRepository->userManageRegion($user)){
            $response['status']  = false;
            $previous .= ' Regions:';
            $previous = $this->getMessage($previous, $regions);
            $response['message'] = $previous;
        }

        if($departments = $this->userRepository->userManageDepartment($user)){
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
