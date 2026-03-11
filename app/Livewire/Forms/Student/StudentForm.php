<?php

namespace App\Livewire\Forms\Student;

use App\Enums\GenderState;
use App\Enums\StatusState;
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
        $result = StudentSaveService::registerStudent($data, $this->id ?? null, $documents ?? null, $this->photo ?? null);

        if ($result) {
            return true;
        }
        return false;
    }

}
