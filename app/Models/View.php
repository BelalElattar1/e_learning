<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable = [
        'student_id',
        'lecture_id',
        'course_id'
    ];
}
