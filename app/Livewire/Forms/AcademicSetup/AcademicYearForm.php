<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Enums\AcademicLevelState;
use App\Events\AuditTableEntryEvent;
use App\Models\AcademicSetup\AcademicYear;
use App\Models\AuditModel\AuditAcademicYear;
use Date;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Enums\StatusState;
use function Illuminate\Support\days;

class AcademicYearForm extends Form
{
    public $id;
    public $start_year_en;
    public $start_year_np;
    public $end_year_en;
    public $end_year_np;
    public $academic_level = AcademicLevelState::SCHOOL->name;
    public $status = StatusState::ACTIVE->name;

    public function generateAcademicYearName(array $data)
    {
        $academic_level = ucfirst(strtolower($data['academic_level']));
        $startYear = date('Y', strtotime($data['start_year_en']));
        $endYear = date('y', strtotime($data['end_year_en']));
        $result = "{$academic_level} FY {$startYear}/{$endYear}";
        return $result;
    }

    public function rules()
    {
        return [
            'start_year_en' => ['required', 'date'],
            'start_year_np' => ['required', 'string'],
            'end_year_en' => ['required', 'date'],
            'end_year_np' => ['required', 'string'],
            'status' => 'required',
            'academic_level' => 'required'
        ];
    }


    public function performSaveAcademicYear($data)
    {
        $data['name'] = $this->generateAcademicYearName($data);
        if ($this->id) {
            $data['updated_by'] = Auth::user()->id;
        } else {
            $data['created_by'] = Auth::user()->id;
        }
        $is_saved = AcademicYear::updateOrCreate(['id' => $this->id], $data);

        AuditTableEntryEvent::dispatch('academic_years', $is_saved, $this->id ? 'edit' : 'create');

        if ($is_saved) {
            return true;
        }

        return true;

    }
}

