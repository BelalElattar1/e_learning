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
        abort_if(!$course, 404, 'This course is not yours.');

        Category::create([
            ...$request->only(['name', 'title', 'course_id']),
            'teacher_id' => $user->teacher->id
        ]);

    }

    public function update($request, Category $category) {

        $user     = auth()->user();
        $category = Category::where('id', $category->id)->where('teacher_id', $user->teacher->id)->first();
        abort_if(!$category, 404, 'This Category is not yours.');

        $category->update([
            ...$request->only(['name', 'title']),
        ]);

    }

    public function destroy(Category $category) {

        $user     = auth()->user();
        $category = Category::where('id', $category->id)->where('teacher_id', $user->teacher->id)->first();

        abort_if(!$category, 404, 'This Category is not yours.');
        $category->delete();

    }

}