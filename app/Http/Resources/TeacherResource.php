<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name'                => $this->name,  
            'email'               => $this->email,  
            'gender'              => $this->gender,  
            'phone_number'        => $this->teacher->phone_number,  
            'Material_name'        => $this->teacher->material->name, 
            'is_subscriber' => $this->teacher->is_subscriber
        ];
    }
}
