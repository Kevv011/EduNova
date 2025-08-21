<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course;

class Module extends Model
{
    protected $filable = [
        'title',
        'description',
        'number_module',
        'course_id',
    ];

    // Relacion de FK con Courses
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
