<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'gender','company_name', 'dob', 'image', 'doc', 'status'
    ];
    
    protected $dates = ['deleted_at'];

    public function address()
    {
        return $this->hasOne(ContactAddress::class);
    }

    /**
     * Get the mergeContact that owns the Contact
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mergeContact(): BelongsTo
    {
        return $this->belongsTo(ContactMerge::class, 'master_contact_id', 'id');
    }
}
