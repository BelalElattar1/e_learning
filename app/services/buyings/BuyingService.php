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
        $courses = Course::whereHas('buyings', function ($query) use($user) {
            $query->where('student_id', $user->student->id);
        })->with('academic_year', 'teacher')->get();

        if(count($courses) > 0) {

            return CourseResource::collection($courses);
            
        } else {

            throw new Exception('Not Found Your Courses');

        }

    }

    public function buying(Course $course) {

        $user = auth()->user();
        $wallet = Wallet::where('student_id', $user->student->id)->where('teacher_id', $course->teacher_id)->first();
        $buying = Buying::where('student_id', $user->student->id)->where('course_id', $course->id)->first();

        if (!$buying) {

            if($wallet->price >= $course->price) {

                DB::transaction(function () use($user, $course, $wallet) {

                    Buying::create([
                        'price'      => $course->price,
                        'student_id' => $user->student->id,
                        'course_id'  => $course->id,
                        'teacher_id' => $course->teacher_id,
                    ]);

                    // بنقص من رصيد الطالب الخاص بهذا المستر
                    $wallet->update([
                        'price' => $wallet->price - $course->price
                    ]);

                    // بنقص من اجمالي رصيد الطالب
                    $user->update([
                        'wallet' => $user->wallet - $course->price
                    ]);

                });

            } else {

                throw new Exception('Your balance is not enough');

            }

        } else {

            throw new Exception('You are already enrolled in this course');

        }

    }

}
