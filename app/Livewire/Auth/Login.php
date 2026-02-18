<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts.auth')]
class Login extends Component
{
    use Toast;
    public $username;
    public $password;
    
    public function mount()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
    }

    public function login()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            session()->flash('success', 'Login successful');
            return redirect()->route('dashboard');
        } else {
            session()->flash('error', 'Invalid credentials');
            $this->error('Invalid username or password', position: "toast-bottom");
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
