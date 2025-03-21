<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [

            'id'   => $this->id,
            'name' => $this->name,
            'link' => $this->when($this->link !== null, $this->link),
            'type' => $this->type,
            'time' => $this->when($this->time !== null, $this->time),
            'mark' => $this->when($this->exam_mark !== null, $this->exam_mark),

            'questions' => $this->when(
                $this->relationLoaded('questions'),
                fn () => $this->questions->map(function ($question) {

                    return [

                        'question_id'   => $question->id,
                        'question_name' => $question->name,
        
                        'chooses' => $this->when(
                            $question->relationLoaded('chooses'),
                            fn () => $question->chooses->map(function ($choose) {

                                return [
                                    'choose_id'   => $choose->id,
                                    'choose_name' => $choose->name,
                                ];

                            })
                        ),
                        
                    ];

                })
            ),

        ];

    }

}
