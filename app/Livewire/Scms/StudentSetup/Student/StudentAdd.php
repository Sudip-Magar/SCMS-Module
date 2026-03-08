<?php

namespace App\Livewire\Scms\StudentSetup\Student;

use App\Enums\GenderState;
use App\Enums\StatusState;
use App\Livewire\Forms\Student\StudentForm;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class StudentAdd extends Component
{
    use WithFileUploads;
    public $admissionNumberingModal = true;
    public $id;
    public $title;
    public StudentForm $studentForm;
    public $status;
    public $gender;

    public $provinces;
    public $districts;
    public $documentNumberings;
    public function mount()
    {
        $this->gender = collect(GenderState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

        if ($this->id) {
            $this->title = "Edit Student ";
        } else {
            $this->title = 'Create Student';
        }

        if(!$this->id){
            $this->documentNumberings = getAdmissionNumbering();
            // dd($this->documentNumberings);
        }
    }

    public function updateNumbering(){
        
        return $this->documentNumberings;
    }

    public function render()
    {
        return view('livewire.scms.student-setup.student.student-add');
    }
}
