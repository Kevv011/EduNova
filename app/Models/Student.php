<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Student extends Model
{
    protected $fillable = [
        'institution',
        'academic_level',
        'date_birth',
        'status',
        'user_id',
    ];

    protected $casts = [
        'date_birth' => 'date',
    ];

    // Relacion tabla pivote course_student
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student')
            ->withPivot('incription_date', 'progress', 'status_progress', 'grade')
            ->withTimestamps();
    }

    // Relacion de FK con Users
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
