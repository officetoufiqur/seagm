<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisionItem extends Model
{
    protected $table = 'vision_items';

    protected $fillable = [
        'vision_id',
        'title',
        'subtitle',
        'image',
    ];

    public function vision()
    {
        return $this->belongsTo(Vision::class);
    }
}
