<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $fillable = ['section', 'title'];

    public function items()
    {
        return $this->hasMany(PlatformItem::class);
    }

    public function images()
    {
        return $this->hasMany(PlatformImage::class);
    }
}
