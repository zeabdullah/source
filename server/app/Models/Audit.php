<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'status',
        'results',
        'overall_score',
    ];

    protected $visible = [
        'id',
        'project_id',
        'name',
        'description',
        'status',
        'results',
        'overall_score',
        'created_at',
        'updated_at',
        'screens',
    ];

    protected $with = ['screens'];

    protected $casts = [
        'results' => 'array',
        'overall_score' => 'decimal:1',
    ];

    protected $attributes = [
        'status' => 'pending',
        'description' => null,
        'results' => null,
        'overall_score' => null,
    ];

    /**
     * Get the project that owns the audit
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the screens that belong to this audit
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Screen, Audit, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function screens(): BelongsToMany
    {
        return $this->belongsToMany(Screen::class, 'audit_screens')
            ->withPivot('sequence_order')
            ->orderBy('audit_screens.sequence_order');
    }

    /**
     * Check if audit is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if audit is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if audit has failed
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }
}
