<?php

namespace App\Models\Student;

use App\Models\AcademicSetup\AcademicStructure;
use Illuminate\Database\Eloquent\Model;

class StudentAcademicStructure extends Model
{
    protected  $guarded = ['id'];

    public function academicStructure(){
        return $this->belongsTo(AcademicStructure::class,'academic_structure_id');
    }
}
