<?php

namespace App\Models\AcademicSetup;

use Illuminate\Database\Eloquent\Model;

class AcademicStructure extends Model
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

    public function year(){
        return $this->belongsTo(AcademicYear::class, 'year_id');
    }

    public function program(){
        return $this->belongsTo(AcademicProgram::class, 'program_id');
    }

    public function faculty(){
        return $this->belongsTo(AcademicFaculty::class, 'faculty_id');
    }

    public function level(){
        return $this->belongsTo(AcademicLevel::class, 'level_id');
    }

    public function room(){
        return $this->belongsTo(AcademicRoom::class, 'room_id');
    }

    public function section(){
        return $this->belongsTo(AcademicSection::class, 'section_id');
    }
}
