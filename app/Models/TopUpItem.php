<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopUpItem extends Model
{
     protected $fillable = [
        'api_id',
        'api_category_id',
        'direct_top_up_id',
        'name',
        'par_value_currency',
        'par_value',
        'currency',
        'unit_price',
        'origin_price',
        'discount_rate',
        'min_amount',
        'max_amount',
        'account_check',
        'status',
        'profit_margin',
        'final_price'
    ];


    public function categoryByApi()
    {
        return $this->belongsTo(DirectTopUp::class, 'api_category_id', 'api_id');
    }

    public function fields()
    {
        return $this->hasMany(TopUpField::class, 'api_item_id', 'api_id');
    }
}
