<?php

namespace App\Livewire\Common;

use Livewire\Component;

class Navbar extends Component
{
     public $menus;
    public function mount()
    {
        $this->menus = config('menu'); // load menu from config/menu.php
        // dd($this->menus);
    }
    public function render()
    {
        return view('livewire.common.navbar');
    }
}
