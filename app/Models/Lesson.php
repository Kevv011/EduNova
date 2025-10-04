<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Module;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Lesson extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'content',
        'subtitle',
        'module_id',
        'url_resource',
    ];

    protected $casts = [
        'url_resource' => 'array',
    ];

    // Relacion de FK con Modules
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'course_id');
    }
}
