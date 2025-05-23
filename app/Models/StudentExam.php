<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    protected $fillable = [
        'degree',
        'exam_id',
        'student_id',
        'teacher_id'
    ];

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function exam() {
        return $this->belongsTo(Section::class, 'exam_id', 'id');
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
