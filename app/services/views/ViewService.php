<?php

namespace App\Services\views;

use Exception;
use App\Models\View;
use App\Models\Section;

class ViewService
{

    public function store($request) {

        $course_id = Section::findOrFail($request['lecture_id'])->category->course->id;
        View::create([
            'student_id' => auth()->user()->student->id,
            'lecture_id' => $request['lecture_id'],
            'course_id'  => $course_id
        ]);

    }

}
