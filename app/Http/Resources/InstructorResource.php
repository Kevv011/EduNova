<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'biography'            => $this->biography,
            'social_links'         => $this->social_links,
            'status'               => $this->status,
            'academic_level'       => $this->academic_level,
            'subject_specialties'  => $this->subject_specialties,
            'schedule_availability' => $this->schedule_availability,
            'subjects'             => $this->subjects,
            'level_education'      => $this->level_education,
            'user'                 => new UserResource($this->whenLoaded('user')),
            'category'             => new CategoryResource($this->whenLoaded('category')),
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
