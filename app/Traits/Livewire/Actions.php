<?php

namespace App\Traits\Livewire;

trait Actions
{
    public function showModal(array $params): void
    {
        $this->emit('app:modal', [
            'icon'    => $params['icon'] ?? null,
            'title'   => $params['title'] ?? null,
            'text'    => $params['text'] ?? null,
            'timeout' => $params['timeout'] ?? null,
        ]);
    }
}
