<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'subtitle',
        'discount_percent',
        'total_coupons',
        'claimed_count',
        'valid_from',
        'valid_to',
        'terms',
        'is_active',
    ];

    protected $casts = [
        'terms' => 'array',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function claims()
    {
        return $this->hasMany(CouponClaim::class);
    }
}
