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
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'institution'    => $this->institution,
            'academic_level' => $this->academic_level,
            'date_birth'     => $this->date_birth?->format('Y-m-d'),
            'status'         => $this->status,
            'interests'      => $this->interests,
            'user'           => new UserResource($this->whenLoaded('user')),
            'courses'        => $this->whenLoaded('courses', function () {
                return $this->courses->map(function ($course) {
                    return [
                        'id'   => $course->id,
                        'name' => $course->name,
                        'pivot' => [
                            'incription_date' => $course->pivot->incription_date,
                            'progress'        => $course->pivot->progress,
                            'status_progress' => $course->pivot->status_progress,
                            'grade'           => $course->pivot->grade,
                        ],
                    ];
                });
            }),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
