<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'status',
        'choose_id',
        'student_id',
        'student_exam_id'
    ];
}
