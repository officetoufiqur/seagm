<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardCategory extends Model
{
    protected $table = 'card_categories';

    protected $fillable = [
        'api_id',
        'name',
        'code',
        'mode',
        'region',
        'publisher',
        'auto_delivery',
        'icon',
    ];

    protected $casts = [
        'auto_delivery' => 'boolean',
        'status' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
