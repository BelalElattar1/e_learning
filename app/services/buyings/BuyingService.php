<?php

namespace App\Services\buyings;

use Exception;
use App\Models\Buying;
use App\Models\Wallet;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CourseResource;

class BuyingService
{

    public function my_courses() {

        $user = auth()->user();

        $courses = Course::whereHas('buyings', function ($query) use ($user) {
            $query->where('student_id', $user->student->id);
        })
        ->select('id', 'title', 'image', 'price', 'description', 'academic_year_id', 'teacher_id')
        ->with([
            'academic_year:id,name', 
            'teacher:id,user_id',
            'teacher.user:id,name' 
        ])
        ->get();

        abort_if($courses->isEmpty(), 404, 'Not Found Your Courses');
        return CourseResource::collection($courses);

    }

    public function buying(Course $course) {

        throw_unless($course->teacher->is_subscriber, new Exception('This course cannot be purchased now.'));

        abort_if(
            Buying::where('student_id', auth()->user()->student->id)->where('course_id', $course->id)->exists(),
            404,
            'You are already enrolled in this course'
        );
        
        $user = auth()->user();
        $wallet = Wallet::where('student_id', $user->student->id)->where('teacher_id', $course->teacher_id)->first();

        throw_unless($wallet && $wallet->price >= $course->price, new Exception('Your balance is not enough'));

        DB::transaction(function () use($user, $course, $wallet) {

            Buying::create([
                'price'      => $course->price,
                'student_id' => $user->student->id,
                'course_id'  => $course->id,
                'teacher_id' => $course->teacher_id,
            ]);

            // بنقص من رصيد الطالب الخاص بهذا المستر
            $wallet->decrement('price', $course->price);

            // بنقص من اجمالي رصيد الطالب
            $user->decrement('wallet', $course->price);

        });

    }

}
