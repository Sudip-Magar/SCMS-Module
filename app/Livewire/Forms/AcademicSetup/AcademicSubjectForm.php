<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Enums\AcademicLevelState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Models\AcademicSetup\AcademicSubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AcademicSubjectForm extends Form
{
    public $id;
    public $name;
    public $short_name;

    public $code;
    public $status = StatusState::ACTIVE->name;
    public $type = AcademicLevelState::SCHOOL->name;

    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:70',
                Rule::unique('academic_subjects', 'name')->ignore($this->id)
            ],
            'short_name' => 'nullable|max:10',
            'status' => 'required',
            'code' => 'required|max:10',
            'type' => 'required'
        ];
    }

    public function performSubjectSave()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'short_name' => $this->short_name,
            'code' => $this->code,
            'status' => $this->status,
            'type' => $this->type
        ];

        if ($this->id) {
            $data['updated_by'] = Auth::user()->id;
        } else {
            $data['created_by'] = Auth::user()->id;
        }

        $is_saved = AcademicSubject::updateOrCreate(['id' => $this->id], $data);

        AuditTableEntryEvent::dispatch('academic_subjects', $is_saved, $this->id ? 'edit' : 'create');

        if ($is_saved) {
            return true;
        }

        return true;
    }
}
