<?php

namespace App\Livewire\Scms\StudentSetup\Student;

use App\Events\AuditTableEntryEvent;
use App\Models\Student\Student;
use App\Models\Student\StudentAcademicStructure;
use App\Models\Student\StudentDocument;
use App\Models\Student\StudentGuardian;
use App\Traits\WithCustomPagination;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Mary\Traits\Toast;

class StudentList extends Component
{
    use Toast, WithCustomPagination;

    public bool $deleteModal = false;
    public array $sortBy = ['column' => 'admission_no', 'direction' => 'asc'];
    public string $search = '';

    public function delete(Student $student)
    {
        DB::beginTransaction();
        try {
            $studentStructure = StudentAcademicStructure::where('student_id', $student->id)->first();
            $studentDocuments = StudentDocument::where('student_id', $student->id)->get();
            $studentGuardians = StudentGuardian::where('student_id', $student->id)->get();

            if ($studentStructure) {
                AuditTableEntryEvent::dispatch('student_academic_structures', $studentStructure, 'delete');
                $studentStructure->delete();
            }

            if ($studentDocuments) {
                foreach ($studentDocuments as $studentDocument) {
                    AuditTableEntryEvent::dispatch('student_documents', $studentDocument, 'delete');
                    $studentDocument->delete();
                }
            }

            if ($studentGuardians) {
                foreach ($studentGuardians as $studentGuardian) {
                    AuditTableEntryEvent::dispatch('student_guardians', $studentGuardian, 'delete');
                    $studentGuardian->delete();
                }
            }
            AuditTableEntryEvent::dispatch('students', $student, 'delete');
            $is_deleted = $student->delete();
            if (!$is_deleted) {
                $this->error('Failed to delete student.', position: 'toast-bottom');
                DB::rollBack();
                return false;
            }
            DB::commit();
            $this->deleteModal = false;
            $this->success('Student deleted successfully.', position: 'toast-bottom');

        } catch (\Throwable $exception) {
            DB::rollBack();
            $this->error("Something went wrong", position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.scms.student-setup.student.student-list', [
            'student_data_list' => $this->studentData(),
            'headers' => $this->headers(),
        ]);
    }

    public function studentData()
    {
        return Student::query()
            ->leftJoin('student_academic_structures as sas', 'sas.student_id', '=', 'students.id')
            ->leftJoin('academic_structures as ac', 'ac.id', '=', 'sas.academic_structure_id')
            ->leftJoin('provinces', 'provinces.id', '=', 'students.province_id')
            ->leftJoin('districts', 'districts.id', '=', 'students.district_id')
            ->selectRaw('
            students.id as id,
            students.admission_no as admission_no,
             CONCAT_WS(" ", students.first_name, students.middle_name, students.last_name) as name,
            ac.name as structure_name,
            students.admission_date_np as admission_date,
            students.date_of_birth_np as date_of_birth,
            students.email as email,
            students.phone as phone,
            provinces.name as province_name,
            districts.name as district_name,
            students.city as city,
            students.ward_no as ward_no,

             CONCAT(
                UCASE(SUBSTRING(students.gender, 1, 1)),
                LOWER(SUBSTRING(students.gender, 2))
            ) as gender,

            CONCAT(
                UCASE(SUBSTRING(students.status, 1, 1)),
                LOWER(SUBSTRING(students.status, 2))
            ) as status
        ')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('students.first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('students.middle_name', 'like', '%' . $this->search . '%')
                        ->orWhere('students.last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('students.admission_no', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage, pageName: 'page');
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => __('Action'), 'class' => 'w-14 text-center', 'sortable' => false],
            ['key' => 'admission_no', 'label' => __('Admission No.'),],
            ['key' => 'name', 'label' => __('Name'), 'sortable' => false],
            ['key' => 'structure_name', 'label' => __('Academic Structure'), 'sortable' => false],
            ['key' => 'admission_date', 'label' => __('Admission Date'), 'sortable' => false],
            ['key' => 'gender', 'label' => __('Gender'), 'sortable' => false],
            ['key' => 'date_of_birth', 'label' => __('Date of Birth'), 'sortable' => false],
            ['key' => 'email', 'label' => __('Email'), 'sortable' => false],
            ['key' => 'phone', 'label' => __('Phone'), 'sortable' => false],
            ['key' => 'province_name', 'label' => __('Province'), 'sortable' => false],
            ['key' => 'district_name', 'label' => __('District'), 'sortable' => false],
            ['key' => 'city', 'label' => __('City'), 'sortable' => false],
            ['key' => 'ward_no', 'label' => __('Ward No'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }
}
