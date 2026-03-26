<?php

namespace App\Livewire\Forms\Student;

use App\Enums\GenderState;
use App\Enums\StatusState;
use App\Models\Student\Student;
use App\Services\StudentSaveService;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Form;

class StudentForm extends Form
{
    use WithFileUploads;

    public $id;
    public $admission_no;
    public $admission_numbering_index;
    public $admission_numbering_id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $photo;
    public $old_photo;
    public $gender = GenderState::MALE->name;
    public $date_of_birth_en;
    public $date_of_birth_np;
    public $email;
    public $phone;
    public $province_id;
    public $district_id;
    public $city;
    public $ward_no;
    public $admission_date_en;
    public $admission_date_np;
    public $status = StatusState::ACTIVE->name;

    public function rules()
    {
        return [
            'admission_no' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'date_of_birth_en' => 'required',
            'date_of_birth_np' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'city' => 'required',
            'ward_no' => 'required',
            'admission_date_en' => 'required',
            'admission_date_np' => 'required',
        ];
    }

    public function performStudentSave($data, $documents)
    {
        if ($this->id) {
            authorizeUserModal('student_setup-student-edit');
        } else {
            authorizeUserModal('student_setup-student-create');
        }
        $result = StudentSaveService::registerStudent($data, $this->id ?? null, $documents ?? null, $this->photo ?? null);

        if ($result) {
            return true;
        }
        return false;
    }

    public function fetchData($id)
    {
        $data = Student::with('studentStructure', 'studentDocuments', 'studentGurdians')->findOrFail($id);
        $this->admission_no = $data->admission_no;
        $this->admission_numbering_index = $data->admission_numbering_index;
        $this->admission_numbering_id = $data->admission_numbering_id;
        $this->admission_date_en = $data->admission_date_en;
        $this->admission_date_np = $data->admission_date_np;
        $this->first_name = $data->first_name;
        $this->middle_name = $data->middle_name;
        $this->last_name = $data->last_name;
        $this->gender = $data->gender;
        $this->date_of_birth_en = $data->date_of_birth_en;
        $this->date_of_birth_np = $data->date_of_birth_np;
        $this->email = $data->email;
        $this->phone = $data->phone;
        $this->old_photo = $data->photo;
        $this->province_id = $data->province_id;
        $this->district_id = $data->district_id;
        $this->city = $data->city;
        $this->ward_no = $data->ward_no;
        $this->status = $data->status;

//        dd($data->studentDocuments);
        return [
            'structureForm' => $data->studentStructure,
            'studentGuardian' => $data->studentGurdians,
            'studentDocuments' => $data->studentDocuments,
        ];
    }

}
