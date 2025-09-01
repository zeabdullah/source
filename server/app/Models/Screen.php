<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Screen extends Model
{
    /** @use HasFactory<\Database\Factories\ScreenFactory> */
    use HasFactory;

    protected $fillable = [
        'section_name',
        'data',
        'figma_node_id',
        'figma_url',
        'description',
    ];

    protected $visible = [
        'id',
        'project_id',
        'section_name',
        'data',
        'figma_node_id',
        'figma_url',
        'description',
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

    /**
     * Scope to search screens by description and section name
     */
    public function scopeSearch(Builder $query, string $searchTerm): Builder
    {
        return $query->where(function (Builder $q) use ($searchTerm) {
            $q->where('description', 'like', "%{$searchTerm}%")
                ->orWhere('section_name', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Scope to filter screens by Figma file
     */
    public function scopeByFigmaFile(Builder $query, string $fileKey): Builder
    {
        return $query->whereHas('project', function (Builder $q) use ($fileKey) {
            $q->where('figma_file_key', $fileKey);
        });
    }

    /**
     * Check if screen has Figma data
     */
    public function hasFigmaData(): bool
    {
        return !empty($this->figma_node_id);
    }

    /**
     * Get Figma frame URL
     */
    public function getFigmaFrameUrl(): ?string
    {
        if (empty($this->figma_node_id) || empty($this->project->figma_file_key)) {
            return null;
        }

        return "https://www.figma.com/file/{$this->project->figma_file_key}/?node-id={$this->figma_node_id}";
    }

    // public function aiChats(): HasMany
    // {
    //     return $this
    //         ->hasMany(AiChat::class, 'commentable_id')
    //         ->where('commentable_type', self::class);
    // }
}

