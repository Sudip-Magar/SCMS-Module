<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Enums\StatusState;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AcademicTimetableForm extends Form
{
    public $id;
    public $name;
    public $structure_id;
    public $status = StatusState::ACTIVE->name;


    public $timetable_id;
    public $daily_schedule_id;
    public $period_no;
    public $subject_id;
    public $teacher_id;
}
