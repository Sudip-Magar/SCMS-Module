<?php

namespace App\Livewire\Scms\AcademicSetup\Timetable;

use Livewire\Component;

class AcademicTimetableSetup extends Component
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
            'list' => authorizeUserCheck('timetable_setup-timetable-list'),
            'create' => authorizeUserCheck('timetable_setup-timetable-create'),
            'edit' => authorizeUserCheck('timetable_setup-timetable-edit'),
            'delete' => authorizeUserCheck('timetable_setup-timetable-delete'),
        ];

        authorizeUserModal('timetable_setup-timetable-list');
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.timetable.academic-timetable-setup');
    }
}
