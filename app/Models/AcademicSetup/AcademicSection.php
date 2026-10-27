<?php

namespace App\Models\AcademicSetup;

use Illuminate\Database\Eloquent\Model;

class AcademicSection extends Model
{
    public $guarded = ['id'];

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'INACTIVE');
    }
}
