<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'price',
        'code',
        'is_active',
        'teacher_id',
    ];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
