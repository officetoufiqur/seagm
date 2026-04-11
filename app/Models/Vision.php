<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vision extends Model
{
    protected $table = 'visions';

    protected $fillable = [
        'description',
    ];

    public function items()
    {
        return $this->hasMany(VisionItem::class);
    }
}
