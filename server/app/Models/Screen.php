<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Screen extends Model
{
    /** @use HasFactory<\Database\Factories\ScreenFactory> */
    use HasFactory;

    protected $fillable = [
        'section_name',
        'data',
    ];

    protected $visible = [
        'id',
        'project_id',
        'section_name',
        'data',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'section_name' => null
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'commentable_id')
            ->where('commentable_type', self::class);
    }

    // public function aiChats(): HasMany
    // {
    //     return $this
    //         ->hasMany(AiChat::class, 'commentable_id')
    //         ->where('commentable_type', self::class);
    // }
}
