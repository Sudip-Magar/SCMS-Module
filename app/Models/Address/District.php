<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected  $guarded = ['id'];

    public function province(){
        $this->belongsTo(Province::class, 'province_id');
    }
}
