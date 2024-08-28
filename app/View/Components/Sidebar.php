<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\View\View;

class Sidebar extends Component
{

    public $roles;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->roles = Role::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar');
    }
}