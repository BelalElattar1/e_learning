<?php

namespace App\Services\reports;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscribe;
use App\Http\Resources\reports\AdminOwnerReportResource;

class ReportService
{

    public function owner_and_admin() {

        $is_admin = auth()->user()->type == 'owner';
        $total_balance           = User::where('id', 1)->where('type', 'owner')->value('wallet');
        $total_active_teachers   = User::whereType('teacher')->whereIs_active(1)->count();
        $total_inactive_teachers = User::whereType('teacher')->whereIs_active(0)->count();
        $total_active_students   = User::whereType('student')->whereIs_active(1)->count();
        $total_inactive_students = User::whereType('student')->whereIs_active(0)->count();
        $total_active_admins     = when($is_admin, User::whereType('admin')->whereIs_active(1)->count());
        $total_inactive_admins   = when($is_admin, User::whereType('admin')->whereIs_active(0)->count());
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

}
