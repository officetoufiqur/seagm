<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExclusiveOffer extends Model
{
    protected $table = 'exclusive_offers';

    protected $fillable = [
        'product_id',
        'title',
        'subtitle',
        'discount_percent',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    

}

