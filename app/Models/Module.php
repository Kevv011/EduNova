<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course;

class Module extends Model
{
    protected $fillable = [
        'title',
        'description',
        'number_module',
        'course_id',
        'status',
    ];

    // Relacion de FK con Courses
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Relacion con Modules
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
