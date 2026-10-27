<?php

namespace App\Livewire\Forms\Student;

use Livewire\Attributes\Validate;
use Livewire\Form;

class StudentDocumentForm extends Form
{
    public $student_id;
    public $document_type;
    public $file_path;
}
