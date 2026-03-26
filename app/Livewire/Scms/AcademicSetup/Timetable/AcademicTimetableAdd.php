<?php

namespace App\Livewire\Scms\AcademicSetup\Timetable;

use App\Enums\ClassTypeState;
use App\Enums\StatusState;
use App\Livewire\Forms\AcademicSetup\AcademicTimetableForm;
use App\Models\AcademicSetup\AcademicStructure;
use Livewire\Component;

class AcademicTimetableAdd extends Component
{
    public $id;
    public $title = "Create Timetable";
    public bool $loading = false;

    public AcademicTimetableForm $timetableForm;

    public $type;
    public $status;

    public function mount()
    {
        if ($this->id) {
            authorizeUserModal('timetable_setup-timetable-edit');
        } else {
            authorizeUserModal('timetable_setup-timetable-create');
        }

        $this->status = backedEnumAsArray(StatusState::cases());
        $this->type = backedEnumAsArray(ClassTypeState::cases());

    }

    public function render()
    {
        return view('livewire.scms.academic-setup.timetable.academic-timetable-add');
    }

    public function fetchStructureData($id)
    {
        $this->loading = true;
        $structure = AcademicStructure::with('year', 'program', 'faculty', 'level', 'room', 'section')->findOrFail($id);
        $this->loading = false;
        return [
            'structure' => $structure
        ];
    }
}
