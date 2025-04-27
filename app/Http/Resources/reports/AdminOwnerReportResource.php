<?php

namespace App\Http\Resources\reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminOwnerReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_balance'            => $this->total_balance,
            'total_active_teachers'    => $this->total_active_teachers,
            'total_inactive_teachers'  => $this->total_inactive_teachers,
            'total_active_students'    => $this->total_active_students,
            'total_inactive_students'  => $this->total_inactive_students,
            'total_active_admins'      => $this->when($this->is_admin, $this->total_active_admins),
            'total_inactive_admins'    => $this->when($this->is_admin, $this->total_inactive_admins),
            'total_balance_this_month' => $this->total_balance_this_month
        ];
    }
}
