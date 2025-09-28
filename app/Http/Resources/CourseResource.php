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
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'description'  => $this->description,
            'areas'        => $this->areas,
            'category'     => new CategoryResource($this->whenLoaded('category')),
            'instructor'   => new UserResource($this->whenLoaded('instructor')),
            'students'     => $this->whenLoaded('students', function () {
                return $this->students->map(function ($student) {
                    return [
                        'id'    => $student->id,
                        'name'  => $student->user->name ?? null,
                        'pivot' => [
                            'incription_date' => $student->pivot->incription_date,
                            'progress'        => $student->pivot->progress,
                            'status_progress' => $student->pivot->status_progress,
                            'grade'           => $student->pivot->grade,
                        ],
                    ];
                });
            }),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
