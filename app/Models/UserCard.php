<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    protected $table = 'user_cards';

    protected $fillable = [
        'user_id',
        'api_id',
        'card_number',
        'card_pin',
        'expired',
    ];

    protected $casts = [
        'expired' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
