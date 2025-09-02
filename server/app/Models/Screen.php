<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Screen extends Model
{
    /** @use HasFactory<\Database\Factories\ScreenFactory> */
    use HasFactory;

    protected $fillable = [
        'section_name',
        'project_id',
        'figma_svg_url',
        'figma_node_id',
        'data',
        'figma_node_id',
        'figma_url',
    ];

    protected $visible = [
        'id',
        'project_id',
        'section_name',
        'data',
        'figma_svg_url',
        'figma_node_id',
        'description',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'section_name' => null,
        'data' => null
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function aiChats()
    {
        return $this->morphMany(AiChat::class, 'commentable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Scope to search screens by description and section name
     */
    #[Scope]
    public function semanticSearch(Builder $query, string $searchTerm): void
    {
        $query->where(function (Builder $q) use ($searchTerm) {
            $q->where('description', 'like', "%{$searchTerm}%")
                ->orWhere('section_name', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Scope to filter screens by Figma file
     */
    #[Scope]
    public function byFigmaFile(Builder $query, string $fileKey): void
    {
        $query->whereHas('project', function (Builder $q) use ($fileKey) {
            $q->where('figma_file_key', $fileKey);
        });
    }

    /**
     * Check if screen has Figma data
     */
    public function hasFigmaData(): bool
    {
        return isset($this->figma_node_id);
    }

    /**
     * Get Figma frame URL
     */
    public function getFigmaFrameUrl(): ?string
    {
        if (!(isset($this->figma_node_id) && isset($this->project->figma_file_key))) {
            return null;
        }

        return "https://www.figma.com/file/{$this->project->figma_file_key}/?node-id={$this->figma_node_id}";
    }
}

