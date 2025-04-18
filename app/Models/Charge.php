<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $fillable = [
        'price',
        'code',
        'student_id',
        'teacher_id'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
