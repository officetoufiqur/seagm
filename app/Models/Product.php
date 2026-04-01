<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'api_id',
        'api_category',
        'category_name',
        'par_value_currency',
        'par_value',
        'currency',
        'unit_price',
        'max_amount',
        'min_amount',
        'origin_price',
        'discount_rate',
        'description',
        'has_stock',
        'image',
        'status',
    ];

    protected $casts = [
        'has_stock' => 'boolean',
        'status' => 'boolean',
    ];

     public function category()
    {
        return $this->belongsTo(CardCategory::class, 'category_id');
    }
    
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
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
