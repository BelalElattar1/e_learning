<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,  
            'email' => $this->email,  
            'phone_number' => $this->phone_number,  
            'father_phone_number' => $this->father_phone_number,  
            'mother_phone_number' => $this->mother_phone_number,  
            'gender' => $this->gender,  
            'card_photo' => $this->card_photo,  
            'wallet' => $this->wallet,  
            'year_name' => $this->academic_year->name,  
            'mayor_name' => $this->mayor->name,  
          ];
    }
}
