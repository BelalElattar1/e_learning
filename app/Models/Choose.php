<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choose extends Model
{
    protected $fillable = [
        'name',
        'status',
        'question_id',
        'teacher_id'
    ];

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
