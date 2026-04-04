<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'card_api_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_api_id', 'api_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
