<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;
use App\Models\User;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'author_id',
        'specialty_id',
        'areas',
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
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
