<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AuditScreen extends Pivot
{
    protected $table = 'audit_screens';

    protected $fillable = [
        'audit_id',
        'screen_id',
        'sequence_order',
    ];

    protected $casts = [
        'sequence_order' => 'integer',
    ];

    /**
     * Get the audit that owns this pivot
     */
    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * Get the screen that owns this pivot
     */
    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }
}
