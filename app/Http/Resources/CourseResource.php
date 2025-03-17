<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'image'        => "storage/$this->image",
            'price'        => $this->price,
            'description'  => $this->description,
            'year_name'    => $this->academic_year->name,
            'teacher_name' => $this->when(
                $this->relationLoaded('teacher'), 
                fn () => $this->teacher->user->name
            ),
        ];
    }
}
