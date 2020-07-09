<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    private string $icon;

    public function __construct($icon = 'alert')
    {
        $this->icon = $icon;
    }

    public function render()
    {
        return view('components.svg.' . $this->icon);
    }
}
