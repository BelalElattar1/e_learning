<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'                  => $this->id,  
            'name'                => $this->name,  
            'email'               => $this->email,  
            'phone_number'        => $this->student->phone_number,  
            'father_phone_number' => $this->student->father_phone,  
            'mother_phone_number' => $this->student->mother_phone,  
            'gender'              => $this->gender,   
            'card_photo'          => url('/api/images/get_private_image/cards/' . $this->student->card_photo),  
            'wallet'              => $this->wallet,  
            'year_name'           => $this->student->academic_year->name,  
            'mayor_name'          => $this->student->mayor->name
          ];

    }

}
