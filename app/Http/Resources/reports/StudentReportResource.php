<?php

namespace App\Http\Resources\reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_balance'           => $this->total_balance,
            'total_courses_purchased' => $this->total_courses_purchased,
            'total_teachers'          => $this->total_teachers
        ];
    }
}
