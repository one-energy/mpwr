<?php

namespace App\Traits\Livewire;

use Illuminate\Pagination\Paginator;

trait FullTable
{
    use \Livewire\WithPagination;

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

        $this->checkQueryString();
    }

    public function checkQueryString()
    {
        //
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

    abstract protected function sortBy();

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
}
