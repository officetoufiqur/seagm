<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = [
        'user_id',
        'receiver_id',
        'subject',
        'question',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class);
    }
}
