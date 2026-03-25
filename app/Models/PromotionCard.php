<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionCard extends Model
{
    protected $table = 'promotion_cards';

    protected $fillable = [
        'promotion_id',
        'title',
        'country',
        'image',
        'sales_count',
        'rating',
        'is_active',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
