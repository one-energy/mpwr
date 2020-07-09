<?php

namespace App\Support;

class Alert
{
    public $color = 'green';

    public $title = '';

    public $description = '';

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

    public function send()
    {
        session()->flash('alert', $this);
    }
}
