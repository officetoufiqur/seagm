<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponClaim extends Model
{
    protected $table = 'coupon_claims';

    protected $fillable = [
        'coupon_id',
        'user_id',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
