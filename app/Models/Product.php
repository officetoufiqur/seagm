<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'code',
        'mode',
        'region',
        'publisher',
        'auto_delivery',
        'image',
        'active',
    ];

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
