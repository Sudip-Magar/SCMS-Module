<?php

namespace App\Livewire\Forms\Numbering;

use App\Enums\AcademicLevelState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Models\Numbering\AdmissionNumbering;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AcademicNumberingForm extends Form
{
    public $id;
    public $academic_level = AcademicLevelState::SCHOOL->name;
    public $prefix;
    public $suffix;
    public $start;
    public $current = 0;
    public $body_length = 0;
    public $total_length = 0;
    public $status = StatusState::ACTIVE->name;

    public function performSave($data)
    {
        if ($this->id) {
            $data['updated_by'] = Auth::user()->id;
        } else {
            $data['created_by'] = Auth::user()->id;
        }

        $is_saved = AdmissionNumbering::updateOrCreate(['id' => $this->id], $data);
        AuditTableEntryEvent::dispatch('admission_numberings', $is_saved, $this->id ? 'edit' : 'create');
        if ($is_saved) {
            return true;
        }

        return true;
    }

    public function rules()
    {
        return [
            "academic_level" => 'required',
            'start' => 'required'
        ];
    }

}
