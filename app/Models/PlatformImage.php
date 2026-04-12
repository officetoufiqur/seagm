<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformImage extends Model
{
    protected $table = 'platform_images';

    protected $fillable = ['platform_id', 'image'];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
}
