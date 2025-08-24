<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instructor extends Model
{
    protected $fillable = [
        'biography',
        'social_links',
        'status',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'social_links' => 'array',
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
