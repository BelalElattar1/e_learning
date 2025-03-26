<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscribeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'start'                => $this->when($this->start !== null, $this->start),
            'end'                  => $this->when($this->end !== null, $this->end),
            'status'               => $this->status,
            'reason_for_rejection' => $this->when($this->reason_for_rejection !== null, $this->reason_for_rejection),
            'teacher_name'         => $this->teacher->user->name
        ];
    }
}
