<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'campaign_id',
        'html'
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
        'html',
        'created_at',
        'updated_at'
    ];
}
