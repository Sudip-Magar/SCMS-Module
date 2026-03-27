<?php

namespace App\Livewire\Scms\StudentModule\Student;

use App\Enums\GenderState;
use App\Enums\StudentDocumentTypeState;
use App\Enums\StudentGuardainRelationState;
use App\Enums\StudentGuardianOccupationState;
use App\Http\Controllers\searchSelect2Controller;
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

    public $guardiansData;

    public function mount()
    {

        if ($this->id) {
            authorizeUserModal('student_setup-student-edit');
            $searchSelect = app(searchSelect2Controller::class);
            $this->title = "Edit Student";
            $this->getStudentData();
            $this->academicStructures = $searchSelect->getAcademicStructure(request());
            $this->provinces = $searchSelect->getProvince(request());
            $this->districts = $searchSelect->getDistrict(request());
        } else {
            authorizeUserModal('student_setup-student-create');
            $this->title = 'Create Student';
            $this->documentForm = [
                [
                    'id' => null,
                    'student_id' => null,
                    'document_type' => StudentDocumentTypeState::BACHELOR_CERTIFICATE->name,
                    'preview' => null,
                    'file_path' => null,
                    'old_file' => null,
                ]
            ];
        }

        $this->gender = backedEnumAsArray(GenderState::cases());
        $this->relations = backedEnumAsArray(StudentGuardainRelationState::cases());
        $this->occupations = backedEnumAsArray(StudentGuardianOccupationState::cases());
        $this->documentTypes = backedEnumAsArray(StudentDocumentTypeState::cases());

        if (!$this->id) {
            $this->documentNumberings = getAdmissionNumbering();
        }
    }

    public function getStudentData()
    {
        $this->studentForm->id = $this->id;
        $data = $this->studentForm->fetchData($this->id);
        $this->structureForm->id = $data['structureForm']->id;
        $this->structureForm->student_id = $data['structureForm']->student_id;
        $this->structureForm->registration_no = $data['structureForm']->registration_no;
        $this->structureForm->academic_structure_id = $data['structureForm']->academic_structure_id;
        $this->structureForm->symbol_no = $data['structureForm']->symbol_no;
        $this->structureForm->roll_no = $data['structureForm']->roll_no;
        $this->guardiansData = $data['studentGuardian'];
//        dd($data['studentDocuments']);

        foreach ($data['studentDocuments'] as $key => $value) {
            $this->documentForm[] = [
                'id' => $value['id'],
                'student_id' => $this->id,
                'document_type' => $value['document_type'],
                'preview' => null,
                'file_path' => null,
                'old_file' => $value['file_path'] ? asset('storage/' . $value['file_path']) : null,
            ];
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
                redirectTo: route('student-module.student-list')
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
        return view('livewire.scms.student-module.student.student-add');
    }
}
