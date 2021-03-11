<?php

namespace App\Support;

class Alert
{
    public $color = 'green';

    public $title = '';

    public $description = '';

    public $livewire;

    public function withColor($color)
    {
        $this->color = $color;

        return $this;
    }

    public function withTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function withDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function livewire($livewire)
    {
        $this->livewire = $livewire;

        return $this;
    }

    public function send()
    {
        if ($this->livewire) {
            $this->livewire->dispatchBrowserEvent('show-alert', $this);
        } else {
            session()->flash('alert', $this);
        }
    }
}
