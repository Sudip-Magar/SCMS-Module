<?php

namespace App\Livewire\Forms\Student;

use Livewire\Attributes\Validate;
use Livewire\Form;

class StudentStructureForm extends Form
{
    public $student_id;
    public $academic_structure_id;
    public $roll_no;
    public $symbol_no;
    public $registration_no;
    
    public function rules(){
        return [
            'student_id' => 'required',
            'academic_structure_id' => 'required',
            'roll_no' => 'nullable|unique:student_academic_structures,roll_no',
            'symbol_no' => 'nullable|unique:student_academic_structures,symbol_no',
            'registration_no' => 'nullable|unique:student_academic_structures,registration_no',
            
        ];
    }
}
