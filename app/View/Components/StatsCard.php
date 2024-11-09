<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatsCard extends Component
{

    public string $title;
    public $count;
    public $total;
    public ?string $percentage;
    public ?string $icon;
    public string $color;

    public function __construct(
        string $title,
        $count,
        $total,
        ?string $percentage = null,
        ?string $icon = null,
        string $color = 'primary'
    ) {
        $this->title = $title;
        $this->count = $count;
        $this->total = $total;
        $this->percentage = $percentage;
        $this->icon = $icon;
        $this->color = $color;
    }


    public function render()
    {
        return view('components.stats-card');
    }
}
