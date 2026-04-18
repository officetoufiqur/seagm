<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StarItem extends Model
{
    protected $table = 'star_items';

    protected $fillable = [
        'star_id',
        'title',
        'subtitle',
        'image',
    ];

    public function star()
    {
        return $this->belongsTo(Star::class);
    }
}
