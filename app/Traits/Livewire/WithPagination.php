<?php

namespace App\Traits\Livewire;

use Livewire\WithPagination as LivewireWithPagination;

trait WithPagination
{
    use LivewireWithPagination;

    public function paginationView()
    {
        return 'vendor.pagination.livewire';
    }
}
