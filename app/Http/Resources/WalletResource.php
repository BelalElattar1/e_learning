<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'price'        => $this->price,
            'teacher_name' => $this->when(
                $this->relationLoaded('teacher'), 
                fn () => $this->teacher->user->name
            ),
            'student_name' => $this->when(
                $this->relationLoaded('student'), 
                fn () => $this->student->user->name
            ),
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
