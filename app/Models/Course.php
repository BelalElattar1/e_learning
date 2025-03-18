<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'image',
        'price',
        'description',
        'academic_year_id',
        'teacher_id'
    ];

    public function academic_year() {
        return $this->belongsTo(AcademicYear::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function buyings() {
        return $this->hasMany(Buying::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }
}
