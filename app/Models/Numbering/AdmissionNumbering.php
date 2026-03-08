<?php

namespace App\Models\Numbering;

use Illuminate\Database\Eloquent\Model;

class AdmissionNumbering extends Model
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
}
