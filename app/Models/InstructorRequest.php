<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InstructorRequest extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'biography',
        'academic_level',
        'subject_specialties',
        'academic_certificate',
        'schedule_availability',
        'subjects',
        'level_education',
        'status',
        'admin_notes',
        'rejection_reason',
        'profile_photo_path',
    ];

    protected $casts = [
        'subject_specialties' => 'array',
        'schedule_availability' => 'array',
        'subjects' => 'array',
        'level_education' => 'array',
    ];
}
