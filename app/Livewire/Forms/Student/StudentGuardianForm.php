<?php

namespace App\Livewire\Forms\Student;

use Livewire\Attributes\Validate;
use Livewire\Form;

class StudentGuardianForm extends Form
{
    public $student_id;
    public $name;
    public $relation;
    public $phone;
    public $occupation;
}
