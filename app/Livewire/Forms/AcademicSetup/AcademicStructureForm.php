<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Enums\AcademicLevelState;
use App\Enums\AcademicSystemState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Models\AcademicSetup\AcademicStructure;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AcademicStructureForm extends Form
{
    public $id;
    public $name;
    public $academic_level = AcademicLevelState::SCHOOL->name;
    public $year_id;
    public $program_id;
    public $faculty_id;
    public $level_id;
    public $room_id;
    public $section_id;
    public $academic_system = AcademicSystemState::YEAR->name;
    public $status = StatusState::ACTIVE->name;

    public function rules()
    {
        return [
            'name' => 'required',
            'academic_level' => 'required',
            'year_id' => 'required',
            'program_id' => 'nullable',
            'faculty_id' => 'nullable',
            'level_id' => 'required',
            'room_id' => 'required',
            'section_id' => 'nullable',
            'academic_system' => 'required',
            'status' => 'required',
        ];
    }

    public function performAcademicStructure($data)
    {
        if ($this->id) {
            $data['updated_by'] = Auth::user()->id;
            } else {
                $data['created_by'] = Auth::user()->id;
                }
                $is_saved = AcademicStructure::updateOrCreate(['id' => $this->id], $data);

        AuditTableEntryEvent::dispatch('academic_structures', $is_saved, $this->id ? 'edit' : 'create');
        if ($is_saved) {
            return true;
        }

        return true;
    }
}