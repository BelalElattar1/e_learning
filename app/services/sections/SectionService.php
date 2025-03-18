<?php

namespace App\Services\sections;

use Exception;
use App\Models\Section;
use App\Models\Category;

class SectionService
{

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
                'exam_mark'   => $request['exam_mark']
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
