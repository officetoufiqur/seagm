<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    protected $fillable = [
        'user_id',
        'fname',
        'lname',
        'address',
        'zip_code',
        'city',
        'state',
        'country',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
