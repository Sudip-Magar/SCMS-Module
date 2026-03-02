<?php

namespace App\Livewire\Scms\AcademicSetup\Timetable;

use App\Enums\ClassTypeState;
use App\Enums\StatusState;
use App\Livewire\Forms\AcademicSetup\AcademicTimetableForm;
use App\Models\AcademicSetup\AcademicStructure;
use Livewire\Component;

class AcademicTimetableAdd extends Component
{
    public $title = "Create Timetable";
    public bool $loading = false;

    public AcademicTimetableForm $timetableForm;

    public $type;
    public $status;

    public function mount()
    {
        $this->status = collect(StatusState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

        $this->type = collect(ClassTypeState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

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
