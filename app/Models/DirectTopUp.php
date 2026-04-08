<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectTopUp extends Model
{
    protected $fillable = [
        'api_id', 'name', 'code', 'mode', 'region', 'auto_delivery', 'description', 'image'
    ];

    public function items()
    {
        return $this->hasMany(TopUpItem::class, 'api_category_id', 'api_id');
    }

    public function topUpReviews()
    {
        return $this->hasMany(TopUpReview::class, 'card_api_id', 'api_id');
    }

    public function orders()
    {
        return $this->morphMany(Order::class, 'product');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
}
