<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $fillable = [
        'start',
        'end',
        'pay_photo',
        'status',
        'reason_for_rejection',
        'teacher_id'
    ];

    public function teacher() {
        $this->belongsTo(Teacher::class);
    }
}
