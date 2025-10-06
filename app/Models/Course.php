<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;
use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'instructor_id',
        'areas',
        'available_status',
        'authorized',
    ];

    protected $casts = [
        'areas' => 'array',
    ];

    // Relacion tabla pivote course_student
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student')
            ->withPivot('incription_date', 'progress', 'status_progress', 'grade')
            ->withTimestamps();
    }

    // Relacion de FK con Categories
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relacion de FK con Instructors
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    // Relacion con modules
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
