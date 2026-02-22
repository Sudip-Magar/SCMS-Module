<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Models\AcademicSetup\AcademicYear;
use App\Models\AuditModel\AuditAcademicYear;
use Date;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Enums\StatusState;

class AcademicYearForm extends Form
{
    public $id;
    public $start_year_en;
    public $start_year_np;
    public $end_year_en;
    public $end_year_np;
    public $status = StatusState::ACTIVE->name;

    public function rules()
    {
        return [
            'start_year_en' => ['required', 'date'],
            'start_year_np' => ['required', 'string'],
            'end_year_en' => ['required', 'date'],
            'end_year_np' => ['required', 'string'],
            'status' => 'required'
        ];
    }


    public function performSaveAcademicYear($data)
    {
        // dd(new AcademicYear());
        if ($this->id) {
            $data['updated_by'] = Auth::user()->id;
        } else {
            $data['created_by'] = Auth::user()->id;
        }
        $is_saved = AcademicYear::updateOrCreate(['id' => $this->id], $data);

        auditTableEntry(AuditAcademicYear::class, $is_saved->toArray(), $this->id ? 'edit' : 'create');

        if($is_saved){
            return true;
        }

        return true;

    }
}

