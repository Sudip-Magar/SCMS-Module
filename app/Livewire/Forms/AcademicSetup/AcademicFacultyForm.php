<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Models\AcademicSetup\AcademicFaculty;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AcademicFacultyForm extends Form
{
    public $id;
    public $name;
    public $short_name;
    public $status = StatusState::ACTIVE->name;

    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:70',
                Rule::unique('academic_faculties', 'name')->ignore($this->id)
            ],

            'short_name' => [
                'required',
                'max:5',
                Rule::unique('academic_faculties', 'short_name')->ignore($this->id)
            ],

            'status' => 'required'
        ];
    }

    public function performFacultySave()
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

        $is_saved = AcademicFaculty::updateOrCreate(['id' => $this->id], $data);

        AuditTableEntryEvent::dispatch('academic_faculties', $is_saved, $this->id ? 'edit' : 'create');

        if ($is_saved) {
            return true;
        }

        return true;
    }
}
