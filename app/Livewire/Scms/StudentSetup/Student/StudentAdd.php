<?php

namespace App\Livewire\Scms\StudentSetup\Student;

use App\Enums\GenderState;
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

    public $provinces;
    public $districts;
    public $academicStructures;
    public $documentNumberings;

    public $documentForm;

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

        $this->documentForm = [
            [
                'student_id' => '',
                'document_type' => StudentDocumentTypeState::BACHELOR_CERTIFICATE->name,
                'preview' => null,
                'file_path' => null,
                'old_file' => null,
            ]
        ];

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
            $errors = [];
            if ($studentErrors = validateField($data['studentData'], $this->studentForm->getRules())) {
                $errors = array_merge(
                    $errors,
                    $studentErrors->getData(true)['errors']
                );
            }

            if ($structureErrors = validateField($data['structureForm'], $this->structureForm->getRules())) {
                $errors = array_merge(
                    $errors,
                    $structureErrors->getData(true)['errors']
                );
            }

            if (!empty($errors)) {
                return response()->json([
                    'errors' => $errors
                ]);
            }
            $is_saved = $this->studentForm->performStudentSave($data, $this->documentForm);

            if (!$is_saved) {
                $this->error("Failed to save student", position: 'toast-bottom');
            }

            $this->success(
                title: 'Student ' . ($this->studentForm->id ? 'Updated' : 'Saved') . ' Successfully',
                description: null,
                position: 'toast-bottom',
                icon: 'o-check-circle',       // Optional (any icon)
                css: 'alert-success',                  // Optional (daisyUI classes)
                timeout: 3000,
                redirectTo: route('student-setup.student-list')
            );

        } catch (\Exception $exception) {
            $this->error('Something went wrong', position: 'toast-bottom');
        }

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
