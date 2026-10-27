<?php

namespace App\Livewire\Scms;

use Livewire\Component;

class Dashboard extends Component
{

    public $allowedPermissions = [
        'list' => false,
        'create' => false,
        'edit' => false,
        'delete' => false,
    ];
    public function mount()
    {
        $this->allowedPermissions = [
            'list' => authorizeUserCheck('dashboard-view-list'),
            'create' => authorizeUserCheck('dashboard-view-create'),
            'edit' => authorizeUserCheck('dashboard-view-edit'),
            'delete' => authorizeUserCheck('dashboard-view-delete'),
        ];
//        dd(auth()->user()->getAllPermissions()->pluck('name'));
        authorizeUserModal('dashboard-view-list');
    }

    public function render()
    {
        return view('livewire.scms.dashboard');
    }
}
