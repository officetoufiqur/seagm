<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectTopUp extends Model
{
    protected $fillable = [
        'api_id', 'name', 'code', 'mode', 'region', 'auto_delivery'
    ];


    public function items()
    {
        return $this->hasMany(TopUpItem::class, 'api_category_id', 'api_id');
    }
}
