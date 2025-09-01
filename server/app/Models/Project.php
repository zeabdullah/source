<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'figma_file_key',
        'figma_file_name',
        'figma_last_synced',
    ];

    /**
     * The attributes that should be visible in arrays.
     *
     * @var list<string>
     */
    protected $visible = [
        'id',
        'owner_id',
        'name',
        'description',
        'figma_file_key',
        'figma_file_name',
        'figma_last_synced',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'description' => null
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'figma_last_synced' => 'datetime',
        ];
    }

    /**
     * Get the project's owner
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the members of the project, using the `User` model.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'project_user',
            'project_id',
            'user_id',
        );
    }

    public function releases(): HasMany
    {
        return $this->hasMany(Release::class);
    }

    public function integrations(): HasMany
    {
        return $this->hasMany(Integration::class);
    }

    public function screens(): HasMany
    {
        return $this->hasMany(Screen::class);
    }

    public function emailTemplates(): HasMany
    {
        return $this->hasMany(EmailTemplate::class);
    }

    /**
     * Connect a Figma file to this project
     */
    public function connectFigmaFile(string $fileKey, string $fileName): bool
    {
        return $this->update([
            'figma_file_key' => $fileKey,
            'figma_file_name' => $fileName,
            'figma_last_synced' => now(),
        ]);
    }

    /**
     * Disconnect Figma file from this project
     */
    public function disconnectFigmaFile(): bool
    {
        return $this->update([
            'figma_file_key' => null,
            'figma_file_name' => null,
            'figma_last_synced' => null,
        ]);
    }

    /**
     * Update the last synced timestamp
     */
    public function updateLastSynced(): bool
    {
        return $this->update(['figma_last_synced' => now()]);
    }

    /**
     * Check if project has a connected Figma file
     */
    public function hasFigmaFile(): bool
    {
        return !empty($this->figma_file_key);
    }
}
