<?php

namespace App\Livewire\Forms\Student;

use Illuminate\Validation\Rule;
use Livewire\Form;

class StudentStructureForm extends Form
{
    public $id;
    public $student_id;
    public $academic_structure_id;
    public $roll_no;
    public $symbol_no;
    public $registration_no;

    public function rules()
    {
        return [
            'academic_structure_id' => ['required'],

            'roll_no' => [
                'nullable',
                Rule::unique('student_academic_structures', 'roll_no')->ignore($this->id),
            ],

            'symbol_no' => [
                'nullable',
                Rule::unique('student_academic_structures', 'symbol_no')->ignore($this->id),
            ],

            'registration_no' => [
                'nullable',
                Rule::unique('student_academic_structures', 'registration_no')->ignore($this->id),
            ],
        ];
    }
}
