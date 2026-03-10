<?php

namespace App\Livewire\Scms\StudentSetup\Student;

use App\Enums\GenderState;
use App\Enums\StatusState;
use App\Enums\StudentDocumentTypeState;
use App\Enums\StudentGuardainRelationState;
use App\Enums\StudentGuardianOccupationState;
use App\Livewire\Forms\Student\StudentForm;
use App\Livewire\Forms\Student\StudentStructureForm;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Mary\Traits\Toast;

class StudentAdd extends Component
{
    use WithFileUploads, Toast;
    public $admissionNumberingModal = true;
    public $id;
    public $title;
    public StudentForm $studentForm;
    public StudentStructureForm $structureForm;
    public $selectedTab = "guardian-tab";
    public $status;
    public $gender;
    public $relations;
    public $occupations;
    public $documentTypes;
    public $documents = [];

    public $provinces;
    public $districts;
    public $academicStructures;
    public $documentNumberings;
    public function mount()
    {
        $this->gender = collect(GenderState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

        $this->relations = collect(StudentGuardainRelationState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

        $this->occupations = collect(StudentGuardianOccupationState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

        $this->documentTypes = collect(StudentDocumentTypeState::cases())
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

        if (!$this->id) {
            $this->documentNumberings = getAdmissionNumbering();
        }
    }

    public function saveStudent($data)
    {
        try {
            if($has_errors = validateField($data['studentData'], $this->studentForm->getRules())){
                return $has_errors;
            }
            dd($data['studentData']);

        } catch (\Exception $exception) {
            $this->error('Something went wrong', position: 'toast-bottom');
        }

    }

    public function removeDocument($index)
    {
        if (isset($this->documents[$index])) {
            unset($this->documents[$index]);
        }

        // reindex array so Livewire stays consistent
        $this->documents = array_values($this->documents);
    }

    public function updateNumbering($id)
    {
        $newNumbering = getAdmissionNumbering($id);
        return $newNumbering;
    }

    public function render()
    {
        return view('livewire.scms.student-setup.student.student-add');
    }
}
