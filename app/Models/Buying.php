<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buying extends Model
{
    protected $fillable = [
        'price',
        'student_id',
        'course_id',
        'teacher_id',
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
