<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Module;

class Lesson extends Model
{
    protected $fillable = [
        'title',
        'content',
        'module_id',
    ];

    // Relacion de FK con Modules
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'course_id');
    }
}
