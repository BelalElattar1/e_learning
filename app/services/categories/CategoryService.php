<?php

namespace App\Services\categories;

use Exception;
use App\Models\Course;
use App\Models\Category;

class CategoryService
{

    public function store($request) {

        $user   = auth()->user();
        $course = Course::where('id', $request['course_id'])->where('teacher_id', $user->teacher->id)->exists();
        if($course) {

            Category::create([
                'name'       => $request['name'],
                'title'      => $request['title'],
                'course_id'  => $request['course_id'],
                'teacher_id' => $user->teacher->id
            ]);

        } else {

            throw new Exception('This course is not yours.');

        }

    }

    public function update($request, Category $category) {

        $user     = auth()->user();
        $category = Category::where('id', $category->id)->where('teacher_id', $user->teacher->id)->first();
        if($category) {

            $category->update([
                'name'  => $request['name'],
                'title' => $request['title']
            ]);

        } else {

            throw new Exception('This Category is not yours.');

        }

    }

    public function destroy(Category $category) {

        $user     = auth()->user();
        $category = Category::where('id', $category->id)->where('teacher_id', $user->teacher->id)->first();
        if($category) {

            $category->delete();

        } else {

            throw new Exception('This Category is not yours.');

        }

    }

}
