<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /**
     * @use HasApiTokens
     * @use HasFactory<\Database\Factories\UserFactory>
     * @use Notifiable
     */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
    ];

    protected $visible = [
        'id',
        'name',
        'email',
        'avatar_url',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'avatar_url' => null,
        'figma_access_token' => null,
    ];



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'figma_access_token' => 'encrypted',
        ];
    }

    /**
     * Get the projects owned by the user.
     */
    public function ownedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    /**
     * Get the projects the user is a member in.
     */
    public function memberProjects()
    {
        return $this->belongsToMany(
            Project::class,
            'project_user',
            'user_id',
            'project_id',
        );
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }
}
