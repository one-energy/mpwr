<?php

namespace App\Http\Livewire\Castle;

use App\Models\Team;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $perPage = 15;

    public $search = '';

    public $sortBy = '';

    public $sortDirection = 'asc';

    public $team = null;

    public $keyword = '';

    public $keywords = [];

    public $filters = [];

    public function mount()
    {
        $this->page          = request()->get('page', $this->page);
        $this->search        = request()->get('search', '');
        $this->sortBy        = request()->get('sortBy', $this->sortBy());
        $this->sortDirection = request()->get('sortDirection', 'asc');
        $this->perPage       = request()->get('perPage', 15);
    }

    public function addKeyword()
    {
        array_push($this->keywords, $this->keyword);
        $this->keyword = '';
    }

    public function applyFilters()
    {
        $this->filters = $this->keywords;
    }

    public function clearFilters()
    {
        $this->keywords = [];
        $this->filters = [];
    }

    public function clearSearch()
    {
        $this->search = '';
    }

    public function updatedSearch()
    {
        $this->page = 1;
    }

    public function updatedPerPage()
    {
        $this->page = 1;
    }

    public function sort($by, $direction)
    {
        $this->sortBy        = $by;
        $this->sortDirection = $direction;
        $this->page          = 1;
    }

    public function getUpdatesQueryString()
    {
        return array_merge([
            'page'          => ['except' => 1],
            'perPage'       => ['except' => 15],
            'search'        => ['except' => ''],
            'sortBy'        => ['except' => $this->sortBy()],
            'sortDirection' => ['except' => 'asc'],
        ], $this->updatesQueryString);
    }

    public function paginationView()
    {
        return 'vendor.pagination.livewire';
    }

    public function initializeWithPagination()
    {
        Paginator::currentPageResolver(fn() => $this->page);

        Paginator::defaultView($this->paginationView());
    }

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
            'users' => User::query()
                ->when(count($this->filters) > 0, function($query) {
                    foreach ($this->filters as $filter) {
                        $query->orWhere(
                            DB::raw('lower(first_name)'),
                            'like',
                            '%' . strtolower($filter) . '%'
                        )
                        ->orWhere(
                            DB::raw('lower(last_name)'),
                            'like',
                            '%' . strtolower($filter) . '%'
                        )
                        ->orWhere(
                            DB::raw('lower(email)'),
                            'like',
                            '%' . strtolower($filter) . '%'
                        );
                    }
                })

                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),

            'teams' => Team::orderBy('name')->cursor()->remember()->all(),
        ]);
    }
}
