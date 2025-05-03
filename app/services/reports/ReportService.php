<?php

namespace App\Services\reports;

use Exception;
use App\Models\User;
use App\Models\Buying;
use App\Models\Course;
use App\Models\Subscribe;
use Illuminate\Support\Facades\DB;
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
        $teacher_id     = $teacher->teacher->id;
        $total_balance  = User::where('id', $teacher->id)->value('wallet');
        $total_courses  = Course::where('teacher_id', $teacher_id)->count();
        $total_students = Buying::where('teacher_id', $teacher_id)
                        ->distinct('student_id')
                        ->count('student_id');

        $lazy_students = DB::select("
            SELECT
                u.name AS Student_Name,
                s.phone_number AS student_phone,
                s.father_phone AS Father_phone,
                s.mother_phone AS Mother_Phone,
                y.name AS Year_Name,
                m.name AS Mayor_Name
            FROM buyings p 
            JOIN students s ON s.id = p.student_id
            JOIN academic_years y ON y.id = s.academic_year_id
            JOIN mayors m ON m.id = s.mayor_id
            JOIN users u ON u.id = s.user_id
            JOIN courses c ON c.id = p.course_id 
            JOIN categories cat ON c.id = cat.course_id
            JOIN sections sec ON cat.id = sec.category_id 
                AND sec.is_active = 1 
                AND sec.type = 'video'
            LEFT JOIN views v ON v.course_id = c.id AND v.student_id = s.id 
            WHERE p.teacher_id = ? 
            GROUP BY s.phone_number, s.father_phone, s.mother_phone
            HAVING COUNT(DISTINCT v.lecture_id) < COUNT(DISTINCT sec.id)
        ", [$teacher_id]);

        return new TeacherReportResource((object) [
            'total_balance'  => $total_balance,
            'total_courses'  => $total_courses,
            'total_students' => $total_students,
            'lazy_students'  => $lazy_students
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
