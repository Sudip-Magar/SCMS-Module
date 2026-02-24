<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Models\AcademicSetup\AcademicProgram;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AcademicProgramForm extends Form
{
    public $id;
    public $name;
    public $short_name;
    public $status = StatusState::ACTIVE->name;

    public function rules()
    {
        return [
            'name' => 'required|max:30',
            'short_name' => 'required|max:5',
            'status' => 'required'
        ];
    }

    public function performProgramSave()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'short_name' => $this->short_name,
            'status' => $this->status
        ];

        if ($this->id) {
            $data['updated_by'] = Auth::user()->id;
        } else {
            $data['created_by'] = Auth::user()->id;
        }

        $is_saved = AcademicProgram::updateOrCreate(['id' => $this->id], $data);

        AuditTableEntryEvent::dispatch('academic_programs', $is_saved, $this->id ? 'edit' : 'create');

        if ($is_saved) {
            return true;
        }

        return true;
    }
}
