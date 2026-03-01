<?php

namespace App\Livewire\Scms\AcademicSetup\Timetable;

use App\Livewire\Forms\AcademicSetup\AcademicTimetableForm;
use App\Models\AcademicSetup\AcademicStructure;
use Livewire\Component;

class AcademicTimetableAdd extends Component
{
    public $title = "Create Timetable";
    public bool $loading = false;

    public AcademicTimetableForm $timetableForm;
    public function render()
    {
        return view('livewire.scms.academic-setup.timetable.academic-timetable-add');
    }

    public function fetchStructureData($id){
        $this->loading = true;
        $structure = AcademicStructure::with('year','program','faculty','level','room','section')->findOrFail($id);
        $this->loading = false;
        return [
            'structure' => $structure
        ];
    }
}
