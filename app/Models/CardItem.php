<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardItem extends Model
{
    protected $fillable = [
        'card_id',
        'name',
        'api_id',
        'api_category_id',
        'category_name',
        'par_value_currency',
        'par_value',
        'currency',
        'unit_price',
        'max_amount',
        'min_amount',
        'origin_price',
        'discount_rate',
        'has_stock',
        'status',
    ];

    protected $casts = [
        'has_stock' => 'boolean',
        'status' => 'boolean',
    ];

     public function card()
    {
        return $this->belongsTo(Card::class,);
    }
    

    public function exclusiveOffer()
    {
        return $this->hasOne(ExclusiveOffer::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
