<?php

namespace App\services\courses;

use Exception;
use App\Models\Course;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;

class CourseService {

    public function show(Course $course) {

        $course = Course::with('categories.sections')->findOrFail($course->id);
        return new CourseResource($course);

    }

    public function index() {

        try {

            JWTAuth::parseToken()->authenticate();
            $user = auth()->user();
    
            $courses = match ($user->type) {

                'student' => Course::whereHas('teacher', fn($q) => $q->where('is_subscriber', true))
                    ->whereDoesntHave('buyings', fn($q) => $q->where('student_id', $user->student->id))
                    ->where('academic_year_id', $user->student->academic_year_id)
                    ->with('academic_year', 'teacher')->get(),
    
                'teacher' => Course::where('teacher_id', $user->teacher->id)
                    ->with('academic_year')->get(),
    
                default => Course::with('academic_year', 'teacher')->get(),
                
            };
    
        } catch (JWTException $e) {

            $courses = Course::whereHas('teacher', fn($q) => $q->where('is_subscriber', true))
                ->with('academic_year', 'teacher')->get();

        }
        
        return count($courses) > 0 ? CourseResource::collection($courses) : throw new Exception('There are no courses');

    }

    public function store($request) {

        $user = auth()->user();
        Course::create([
            'title'            => $request['title'],
            'description'      => $request['description'],
            'image'            => store_image_public($request['image'], 'courses'),
            'price'            => $request['price'],
            'academic_year_id' => $request['academic_year_id'],
            'teacher_id'       => $user->teacher->id
        ]);

    }

    public function update($request, Course $course) {

        $user = auth()->user();
        $course = Course::where('id', $course->id)->where('teacher_id', $user->teacher->id)->first();
        if($course) {

            Storage::disk('public')->delete("$course->image");
            $course->update([
                'title'            => $request['title'],
                'description'      => $request['description'],
                'image'            => store_image_public($request['image'], 'courses'),
                'price'            => $request['price'],
                'academic_year_id' => $request['academic_year_id'],
                'teacher_id'       => $user->teacher->id
            ]);

        } else {

            throw new Exception('You do not have permission to modify this course or it does not exist.');

        }

    }

}