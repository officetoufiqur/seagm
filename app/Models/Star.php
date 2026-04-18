<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    protected $table = 'stars';

    protected $fillable = [
        'heading',
        'title',
        'subtitle',
        'image',
    ];

    public function items()
    {
        return $this->hasMany(StarItem::class);
    }
}
