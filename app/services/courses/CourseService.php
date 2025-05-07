<?php

namespace App\services\courses;

use Exception;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;

class CourseService {

    public function best_seller(AcademicYear $year) {

        return DB::table('courses')
            ->select(
                'courses.id',
                'courses.title',
                'courses.image',
                'courses.price',
                'courses.description',
                'users.name as Teacher_Name',
                DB::raw('COUNT(buyings.id) as total_sales')
            )
            ->join('buyings', 'courses.id', '=', 'buyings.course_id')
            ->join('teachers', 'courses.teacher_id', '=', 'teachers.id')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->where('courses.academic_year_id', $year->id)
            ->groupBy('courses.id', 'courses.title', 'courses.image', 'courses.price', 'courses.description', 'users.name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

    }

    public function show(Course $course) {

        $user  = auth()->user();
        $course = Course::where('id', $course->id)
        ->with(['teacher:id,user_id', 'teacher.user:id,name', 'academic_year:id,name', 'categories.sections' => function ($q) use ($user) {
            if (!$user || $user->type === 'student') {
                $q->where('is_active', true);
            }
        }])->first();

        return $course ? new CourseResource($course) : throw new Exception('There are no courses');

    }

    public function index(Teacher $teacher) {

        $query = Course::select('id', 'title', 'image', 'price', 'description', 'academic_year_id', 'teacher_id')
                ->with(['academic_year:id,name', 'teacher:id,user_id', 'teacher.user:id,name']);

        try {

            JWTAuth::parseToken()->authenticate();
            $user = auth()->user();
    
            match ($user->type) {
                'student' => $query->whereRelation('teacher', 'is_subscriber', true)
                            ->whereDoesntHave('buyings', fn ($q) => $q->where('student_id', $user->student->id))
                            ->where('academic_year_id', $user->student->academic_year_id)
                            ->where('teacher_id', $teacher->id),
                
                'teacher' => $query->where('teacher_id', $user->teacher->id),

                default   => $query->where('teacher_id', $teacher->id)
            };
    
        } catch (JWTException $e) {

            $query->whereRelation('teacher', 'is_subscriber', true)->where('teacher_id', $teacher->id);

        }

        $courses = $query->get();
        abort_if($courses->isEmpty(), 404, 'There are no courses');
        return CourseResource::collection($courses);

    }

    public function store($request) {

        $user = auth()->user();
        Course::create([
            ...$request->only(['title', 'description', 'price', 'academic_year_id']),
            'image'            => store_image_public($request['image'], 'courses'),
            'teacher_id'       => $user->teacher->id
        ]);

    }

    public function update($request, Course $course) {

        $user = auth()->user();
        abort_if($course->teacher_id !== $user->teacher->id, 404, 'You do not have permission to modify this course or it does not exist.');

        Storage::disk('public')->delete($course->image);
        $course->update([
            ...$request->only(['title', 'description', 'price', 'academic_year_id']),
            'image'            => store_image_public($request['image'], 'courses')
        ]);

    }

}