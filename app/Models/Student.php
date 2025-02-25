<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'phone_number',
        'father_phone',
        'mother_phone',
        'school_name',
        'father_job',
        'gender',
        'user_id',
        'mayor_id',
        'academic_id',
        'card_photo'
    ];

    public function mayor() {
        return $this->belongsTo(Mayor::class);
    }

    public function academic_year() {
        return $this->belongsTo(AcademicYear::class);
    }
}
