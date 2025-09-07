<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChat extends Model
{
    /** @use HasFactory<\Database\Factories\AiChatFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'content'
    ];

    /**
     * The attributes that should be visible in arrays.
     *
     * @var list<string>
     */
    protected $visible = [
        'id',
        'user_id',
        'commentable_id',
        'commentable_type',
        'sender',
        'content',
        'created_at',
        'updated_at'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'content' => null
    ];

    /**
     * Get the chat message's user, if any.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent commentable model (morph-to).
     */
    public function commentable()
    {
        return $this->morphTo();
    }
}
