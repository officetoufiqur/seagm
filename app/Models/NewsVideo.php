<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsVideo extends Model
{
    protected $fillable = [
        'title',
        'video_url',
        'thumbnail',
    ];
}
