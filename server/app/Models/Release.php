<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    /** @use HasFactory<\Database\Factories\ReleaseFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'version',
        'description',
        'tags',
    ];

    protected $visible = [
        'id',
        'project_id',
        'version',
        'description',
        'tags',
        'screens',
        'emailTemplates',
        'project',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'description' => null,
        'tags' => null,
    ];

    protected $with = ['screens', 'emailTemplates'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function screens()
    {
        return $this->morphedByMany(Screen::class, 'releasable');
    }

    public function emailTemplates()
    {
        return $this->morphedByMany(EmailTemplate::class, 'releasable');
    }
}
