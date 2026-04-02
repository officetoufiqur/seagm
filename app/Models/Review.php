<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'card_item_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function cardItem()
    {
        return $this->belongsTo(CardItem::class, 'card_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
