<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name',
        'type',
        'link',
        'time',
        'exam_mark',
        'category_id',
        'teacher_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
