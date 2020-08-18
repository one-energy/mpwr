<?php

namespace App\Http\Livewire\Castle;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Permission extends Component
{
    use WithPagination;

    public $perPage = 15;

    public $search = '';

    public $sortBy = '';

    public $sortDirection = 'asc';

    public function mount()
    {
        $this->page          = request()->get('page', $this->page);
        $this->search        = request()->get('search', '');
        $this->sortBy        = request()->get('sortBy', $this->sortBy());
        $this->sortDirection = request()->get('sortDirection', 'asc');
        $this->perPage       = request()->get('perPage', 15);
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

    public function sortBy()
    {
        return 'first_name';
    }

    public function render()
    {
        return view('livewire.castle.permission', [
            'users' => User::query()
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
