<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards';

    protected $fillable = [
        'api_id',
        'name',
        'code',
        'mode',
        'region',
        'publisher',
        'auto_delivery',
        'image',
    ];

    protected $casts = [
        'auto_delivery' => 'boolean',
        'status' => 'boolean',
    ];

    public function cardItems()
    {
        return $this->hasMany(CardItem::class, 'api_category_id', 'api_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'card_api_id', 'api_id');
    }

    public function orders()
    {
        return $this->morphMany(Order::class, 'product');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
