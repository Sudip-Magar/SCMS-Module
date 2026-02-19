<?php

namespace App\Livewire\Forms\AcademicSetup;

use Date;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Enums\StatusState;

class AcademicYearForm extends Form
{
    public $id;
    public $start_year;

    public $end_year;
    public $status = StatusState::ACTIVE->name;
}
