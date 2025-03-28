<?php

namespace App\Services\sections;

use Exception;
use App\Models\Buying;
use App\Models\Section;
use App\Models\Category;
use App\Http\Resources\SectionResource;

class SectionService
{

    public function show(Section $section) {

        $user = auth()->user();
        if($user->type == 'student') {

            $course_id = $section->category->course->id;
            $isEnrolled = Buying::where([
                'student_id' => $user->student->id,
                'course_id'  => $course_id
            ])->exists();
    
            if (!$isEnrolled) {
                throw new Exception('You are not enrolled in this course');
            }

        }

        $query = Section::where('id', $section->id)->where('is_active', true);

        if ($section->type == 'exam') {
            $query->with('questions.chooses');
        }
    
        $section = $query->first();
        
        return $section ? new SectionResource($section) : throw new Exception('Not Found');

    }

    public function store($request) {

        $user = auth()->user();
        $category = Category::where('id', $request['category_id'])->where('teacher_id', $user->teacher->id)->exists();
        if($category) {

            Section::create([
                'name'        => $request['name'],
                'type'        => $request['type'],
                'link'        => $request['link'],
                'time'        => $request['time'],
                'exam_mark'   => $request['exam_mark'],
                'is_active'   => $request['is_active'],
                'category_id' => $request['category_id'],
                'teacher_id'  => $user->teacher->id
            ]);

        } else {

            throw new Exception('This Category is not yours.');

        }

    }

    public function update($request, Section $section) {

        $user     = auth()->user();
        $section = Section::where('id', $section->id)->where('teacher_id', $user->teacher->id)->first();
        if($section) {

            $section->update([
                'name'        => $request['name'],
                'type'        => $request['type'],
                'link'        => $request['link'],
                'time'        => $request['time'],
                'exam_mark'   => $request['exam_mark'],
                'is_active'   => $request['is_active']
            ]);

        } else {

            throw new Exception('This Category is not yours.');

        }

    }

    public function destroy(Section $section) {

        $user     = auth()->user();
        $section = Section::where('id', $section->id)->where('teacher_id', $user->teacher->id)->first();
        if($section) {

            $section->delete();

        } else {

            throw new Exception('This Section is not yours.');

        }

    }

}
