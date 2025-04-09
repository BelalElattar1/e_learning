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
    
            throw_unless($isEnrolled, new Exception('You are not enrolled in this course.'));

        }

        $section = $section->where('id', $section->id)
                    ->where('is_active', true)
                    ->when($section->type === 'exam', function ($q) {
                        $q->with([
                            'questions:id,exam_id,name',
                            'questions.chooses:id,question_id,name'
                        ]);
                    })
                    ->firstOrFail();
        
        return new SectionResource($section);

    }

    public function store($request) {

        $user = auth()->user();
        $category = Category::where('id', $request['category_id'])->where('teacher_id', $user->teacher->id)->exists();
        throw_unless($category, new Exception('This Category is not yours.'));

        Section::create([
            ...$request->only(['name', 'type', 'link', 'time', 'exam_mark', 'is_active', 'category_id']),
            'teacher_id'  => $user->teacher->id
        ]);

    }

    public function update($request, Section $section) {

        $user     = auth()->user();
        abort_if($section->teacher_id !== $user->teacher->id, 404, 'This Section is not yours.');

        $section->update([
            'name'        => $request['name'],
            'type'        => $request['type'],
            'link'        => $request['link'],
            'time'        => $request['time'],
            'exam_mark'   => $request['exam_mark'],
            'is_active'   => $request['is_active']
        ]);

    }

    public function destroy(Section $section) {

        $user = auth()->user();
        abort_if($section->teacher_id !== $user->teacher->id, 404, 'This Section is not yours.');
        $section->delete();

    }

}
