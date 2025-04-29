<?php

namespace App\Services\reports;

use Exception;
use App\Models\User;
use App\Models\Buying;
use App\Models\Course;
use App\Models\Subscribe;
use App\Http\Resources\reports\StudentReportResource;
use App\Http\Resources\reports\TeacherReportResource;
use App\Http\Resources\reports\AdminOwnerReportResource;

class ReportService
{

    public function owner_and_admin() {

        $is_admin                 = auth()->user()->type == 'owner';
        $total_balance            = User::where('id', 1)->where('type', 'owner')->value('wallet');
        $total_active_teachers    = User::whereType('teacher')->whereIs_active(1)->count();
        $total_inactive_teachers  = User::whereType('teacher')->whereIs_active(0)->count();
        $total_active_students    = User::whereType('student')->whereIs_active(1)->count();
        $total_inactive_students  = User::whereType('student')->whereIs_active(0)->count();
        $total_active_admins      = when($is_admin, User::whereType('admin')->whereIs_active(1)->count());
        $total_inactive_admins    = when($is_admin, User::whereType('admin')->whereIs_active(0)->count());
        $total_balance_this_month = Subscribe::whereStatus('active')    
                                    ->whereMonth('start', now()->month)
                                    ->whereYear('start', now()->year)
                                    ->count() * 150;

        return new AdminOwnerReportResource((object) [
            'is_admin'                => $is_admin,
            'total_balance'           => $total_balance,
            'total_active_teachers'   => $total_active_teachers,
            'total_inactive_teachers' => $total_inactive_teachers,
            'total_active_students'   => $total_active_students,
            'total_inactive_students' => $total_inactive_students,
            'total_active_admins'     => $total_active_admins,
            'total_inactive_admins'   => $total_inactive_admins,
            'total_balance_this_month'=> $total_balance_this_month
        ]);

    }

    public function teacher() {

        $teacher        = auth()->user();
        $total_balance  = User::where('id', $teacher->id)->value('wallet');
        $total_courses  = Course::where('teacher_id', $teacher->teacher->id)->count();
        $total_students = Buying::where('teacher_id', $teacher->teacher->id)
                        ->distinct('student_id')
                        ->count('student_id');
        // $lazy_students = '';

        return new TeacherReportResource((object) [
            'total_balance'  => $total_balance,
            'total_courses'  => $total_courses,
            'total_students' => $total_students
        ]);

    }


    public function student() {

        $student                  = auth()->user();
        $total_balance            = User::where('id', $student->id)->value('wallet');
        $total_courses_purchased  = Buying::where('student_id', $student->student->id)->count();
        $total_teachers           = Buying::where('student_id', $student->student->id)
                                    ->distinct('teacher_id')
                                    ->count('teacher_id');

        return new StudentReportResource((object) [
            'total_balance'            => $total_balance,
            'total_courses_purchased'  => $total_courses_purchased,
            'total_teachers'           => $total_teachers
        ]);

    }

}
