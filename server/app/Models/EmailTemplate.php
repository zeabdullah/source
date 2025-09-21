<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EmailTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\EmailTemplateFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'section_name',
        'project_id',
        'campaign_id',
        'brevo_template_id',
        'html_content',
        'thumbnail_url'
    ];

    /**
     * The attributes that should be visible in arrays.
     *
     * @var list<string>
     */
    protected $visible = [
        'id',
        'project_id',
        'section_name',
        'campaign_id',
        'brevo_template_id',
        'html_content',
        'thumbnail_url',
        'created_at',
        'updated_at'
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

    public function releases()
    {
        return $this->morphToMany(Release::class, 'releasable');
    }

    /**
     * Scope to search email templates by section name
     */
    #[Scope]
    public function semanticSearch(Builder $query, string $searchTerm): void
    {
        $query->where(function (Builder $q) use ($searchTerm) {
            $q->where('section_name', 'like', "%{$searchTerm}%");
        });
    }

}
