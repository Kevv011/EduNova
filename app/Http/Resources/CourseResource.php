<?php

namespace App\Http\Resources;

use App\Models\Instructor;
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
            'authorized'   => $this->authorized,
            'available_status' => $this->available_status,
            'category'     => new CategoryResource($this->whenLoaded('category')),
            'instructor'   => new InstructorResource($this->whenLoaded('instructor')),
            'image' => $this->getFirstMediaUrl('course_images') ?: null,
            'modules' => ModuleResource::collection($this->whenLoaded('modules')),
            'students'     => $this->whenLoaded('students', function () {
                return $this->students->map(function ($student) {
                    return [
                        'id'    => $student->id,
                        'name'  => $student->user->name ?? null,
                        'student_info' => new StudentResource($student),
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
