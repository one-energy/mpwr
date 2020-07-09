<?php

namespace App\Http\Livewire\Castle;

use App\Models\Invitation;
use App\Traits\Livewire\WithPagination;
use Livewire\Component;

class MastersInvitations extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.castle.masters-invitations', [
            'invitations' => Invitation::query()->latest()->paginate(),
        ]);
    }

    public function delete($id)
    {
        Invitation::query()->find($id)->delete();
    }
}
