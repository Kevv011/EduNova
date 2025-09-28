<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Instructor extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'biography',
        'social_links',
        'status',
        'user_id',
        'category_id',
        'academic_level',
        'subject_specialties',
        'schedule_availability',
        'subjects',
        'level_education',
    ];

    protected $casts = [
        'social_links' => 'array',
        'subject_specialties' => 'array',
        'schedule_availability' => 'array',
        'level_education' => 'array',
    ];

    // Relacion de FK con Users
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relacion de FK con Categories
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
