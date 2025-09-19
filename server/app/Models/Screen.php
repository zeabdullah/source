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
        'project_id',
        'section_name',
        'figma_svg_url',
        'figma_node_id',
        'figma_file_key',
        'data',
    ];

    protected $visible = [
        'id',
        'project_id',
        'section_name',
        'data',
        'figma_svg_url',
        'figma_node_id',
        'figma_file_key',
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

    public function audits()
    {
        return $this->belongsToMany(Audit::class, 'audit_screens')
            ->withPivot('sequence_order')
            ->orderBy('audit_screens.sequence_order');
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

    /**
     * Serialize Figma frame data for AI analysis
     */
    public function serializeForAudit(): string
    {
        if (!$this->data || !is_array($this->data)) {
            return "Screen: {$this->section_name}\nNo Figma data available.";
        }

        return json_encode($this->data);
    }
}

