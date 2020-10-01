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
        if(user()->office_id || user()->role == "Department Manager"){
            $query = User::with('office')->select('users.*');
            if(user()->role == "Office Manager"){
                $query->whereOfficeId(user()->office_id);
            }
            if(user()->role == "Region Manager"){
                $regionId = user()->id;
                $query->join('offices', 'users.office_id', '=', 'offices.id');
                $query->join('regions', function($join) use ($regionId) {
                    $join->on('offices.region_id', '=', 'regions.id')
                        ->where('regions.region_manager_id', "=", $regionId);

                });
            }
            if(user()->role == "Department Manager"){
                $query->whereDepartmentId(user()->department_id);
            }
        }else{
            $query = User::query()->whereId(0);
        }
        
        return view('livewire.castle.users', [
            'users' => $query
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}