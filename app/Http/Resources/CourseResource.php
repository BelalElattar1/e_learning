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
            'year_name'    => $this->when($this->relationLoaded('academic_year'), $this->academic_year->name),
            'teacher_name' => $this->when($this->relationLoaded('teacher'), $this->teacher->user->name),

            'categories' => $this->when(
                $this->relationLoaded('categories'),
                fn () => $this->categories->map(function ($category) {

                    return [

                        'category_id'    => $category->id,
                        'category_name'  => $category->name,
                        'category_title' => $category->title,
        
                        'sections' => $this->when(
                            $category->relationLoaded('sections'),
                            fn () => $category->sections->map(function ($section) {

                                return [
                                    'section_id'   => $section->id,
                                    'section_name' => $section->name,
                                    'section_type' => $section->type
                                ];

                            })
                        ),
                        
                    ];

                })
            ),

        ];

    }

}
