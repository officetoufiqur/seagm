<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformItem extends Model
{
    protected $fillable = ['platform_id', 'title', 'icon'];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
}
