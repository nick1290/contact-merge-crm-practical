<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMerge extends Model
{

    protected $fillable = [
        'master_contact_id', 'merged_contact_id', 'merged_contact_data'
    ];


    /**
     * Get the masterContact that owns the ContactMerge
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function masterContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'master_contact_id', 'id');
    }

    /**
     * Get the mergedContact that owns the ContactMerge
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mergedContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'merged_contact_id', 'id');
    }
}
