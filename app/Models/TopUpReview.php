<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopUpReview extends Model
{
    protected $fillable = [
        'card_api_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function topUp()
    {
        return $this->belongsTo(DirectTopUp::class, 'card_api_id', 'api_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
