<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Enums\AcademicLevelState;
use App\Enums\shiftStatusState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Models\AcademicSetup\AcademicDailySchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AcademicDailySchedulForm extends Form
{
    public $id;
    public $name;
    public $academic_level = AcademicLevelState::SCHOOL->name;
    public $day;
    public $total_period;
    public $shift = ShiftStatusState::MORNING->name;
    public $status = StatusState::ACTIVE->name;

    public function rules()
    {
        return [
            'academic_level' => ['required', 'string'],
            'day' => ['required', 'string'],
            'total_period' => [
                'required',
                'integer',
                Rule::unique('academic_daily_schedules')
                    ->where(function ($query) {
                        return $query->where('academic_level', $this->academic_level)
                            ->where('day', $this->day);
                    })
                    ->ignore($this->id), // <- ignores current record if updating
            ],
        ];
    }

    public function performScheduleSave()
    {
        $this->validate();

        $data = [
            'academic_level' => $this->academic_level,
            'day' => $this->day,
            'total_period' => $this->total_period,
            'shift' => $this->shift,
            'status' => $this->status,
        ];

        $data['name'] = $this->generateScheduleName($data);

        if ($this->id) {
            $data['updated_by'] = Auth::user()->id;
        } else {
            $data['created_by'] = Auth::user()->id;
        }

        $is_saved = AcademicDailySchedule::updateOrCreate(['id' => $this->id], $data);

        AuditTableEntryEvent::dispatch('academic_daily_schedules', $is_saved, $this->id ? 'edit' : 'create');

        if ($is_saved) {
            return true;
        }

        return true;
    }

    public function generateScheduleName($data){
        $academic_level = ucfirst(strtolower($data['academic_level']));
        $day = ucfirst(strtolower($data['day']));
        $shift = ucfirst(strtolower($data['shift']));
        $total_period = $data['total_period'];

        $result = "{$academic_level}-{$day}-{$shift}({$total_period} Period)";
        return $result;
    }
}
