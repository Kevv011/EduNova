<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'subtitle'     => $this->subtitle,
            'content'   => $this->content,
            'url_resource' => $this->url_resource,
            'module'    => new ModuleResource($this->whenLoaded('module')),
            'media'        => $this->getMedia('lessons')->map(function ($media) {
                return [
                    'id'   => $media->id,
                    'name' => $media->file_name,
                    'url'  => $media->getUrl(),
                    'type' => $media->mime_type,
                    'size' => $media->size,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
