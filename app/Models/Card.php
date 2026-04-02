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
        return $this->hasMany(CardItem::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
