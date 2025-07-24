<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contact_id', 'city', 'landmark', 'pincode',
        'state', 'country', 'address'
    ];

    protected $dates = ['deleted_at'];
    
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
