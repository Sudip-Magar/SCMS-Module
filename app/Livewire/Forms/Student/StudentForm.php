<?php

namespace App\Livewire\Forms\Student;

use App\Enums\StatusState;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Form;

class StudentForm extends Form
{
    use WithFileUploads;
    public $id;
    public $admission_no;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $photo;
    public $old_photo;
    public $gender;
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

}
