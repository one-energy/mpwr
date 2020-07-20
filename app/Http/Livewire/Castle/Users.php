<?php

namespace App\Http\Livewire\Castle;

use App\Models\Team;
use App\Models\User;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Users extends Component
{
    use FullTable;

    public $team;

    public $updatesQueryString = [
        'team' => ['except' => '0'],
    ];

    public function checkQueryString()
    {
        $this->team = request()->team;
    }

    public function sortBy()
    {
        return 'first_name';
    }

    public function render()
    {
        return view('livewire.castle.users', [
            'users' => User::search($this->search)
                ->when($this->team, function(Builder $query) {
                    $query->whereHas('teams', function(Builder $query) {
                        $query->whereId($this->team);
                    })
                    ->addSelect(['role' => DB::table('team_user')
                        ->whereRaw('user_id = users.id')
                        ->where('team_id', $this->team)
                        ->select(['role'])
                        ->limit(1),
                    ]);
                })
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),

            'teams' => Team::orderBy('name')->cursor()->remember()->all(),
        ]);
    }
}
