<?php

namespace App\Http\Resources\reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_balance'  => $this->total_balance,
            'total_courses'  => $this->total_courses,
            'total_students' => $this->total_students,
            'lazy_students'  => $this->lazy_students
        ];
    }
}
