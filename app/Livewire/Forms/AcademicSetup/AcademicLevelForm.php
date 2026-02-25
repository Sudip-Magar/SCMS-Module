<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Enums\AcademicLevelState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Models\AcademicSetup\AcademicLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AcademicLevelForm extends Form
{
    public $id;
    public $name;
    public $short_name;
    public $status = StatusState::ACTIVE->name;
    public $type = AcademicLevelState::SCHOOL->name;

    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:70',
                Rule::unique('academic_levels', 'name')->ignore($this->id)
            ],

            'short_name' => [
                'required',
                'max:5',
                Rule::unique('academic_levels', 'short_name')->ignore($this->id)
            ],

            'status' => 'required',
            'type' => 'required'
        ];
    }

    public function performLevelSave()
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

        $is_saved = AcademicLevel::updateOrCreate(['id' => $this->id], $data);

        AuditTableEntryEvent::dispatch('academic_levels', $is_saved, $this->id ? 'edit' : 'create');

        if ($is_saved) {
            return true;
        }

        return true;
    }
}
