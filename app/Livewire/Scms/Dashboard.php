<?php

namespace App\Livewire\Scms;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount(){
//        dd(Auth::user()->profile);
    }
    public function render()
    {
        return view('livewire.scms.dashboard');
    }
}
