<?php

namespace App\Models\Student;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $guarded = ['id'];

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'INACTIVE');
    }

    public function studentStructure(){
        return $this->hasOne(StudentAcademicStructure::class,'student_id');
    }

    public function studentDocuments(){
        return $this->hasMany(StudentDocument::class,'student_id');
    }

    public function studentGurdians(){
        return $this->hasMany(StudentGuardian::class,'student_id');
    }

    public function user() {
        return $this->morphOne(User::class, 'profile');
    }
}
