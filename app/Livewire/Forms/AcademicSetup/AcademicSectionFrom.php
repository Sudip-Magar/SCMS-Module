<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Enums\AcademicSectionlState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Models\AcademicSetup\AcademicSection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AcademicSectionFrom extends Form
{
    public $id;
    public $name;
    public $short_name;
    public $status = StatusState::ACTIVE->name;
    public $type = AcademicSectionlState::SCHOOL->name;

    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:70',
                Rule::unique('academic_sections', 'name')->ignore($this->id)
            ],

            'short_name' => [
                'required',
                'max:5',
                Rule::unique('academic_sections', 'short_name')->ignore($this->id)
            ],

            'status' => 'required',
            'type' => 'required'
        ];
    }

    public function performSectionSave()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'short_name' => $this->short_name,
            'status' => $this->status,
            'type' => $this->type
        ];

        if ($this->id) {
            $data['updated_by'] = Auth::user()->id;
        } else {
            $data['created_by'] = Auth::user()->id;
        }

        $is_saved = AcademicSection::updateOrCreate(['id' => $this->id], $data);

        AuditTableEntryEvent::dispatch('academic_sections', $is_saved, $this->id ? 'edit' : 'create');

        if ($is_saved) {
            return true;
        }

        return true;
    }
}
