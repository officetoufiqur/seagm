<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopUpField extends Model
{
    protected $fillable = [
        'api_item_id',
        'top_up_item_id',
        'name',
        'type',
        'label',
        'label_zh',
        'multiline',
        'placeholder',
        'prefix',
        'position'
    ];

    public function itemByApi()
    {
        return $this->belongsTo(TopUpItem::class, 'api_item_id', 'api_id');
    }
}
