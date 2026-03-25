<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    protected $fillable = [
        'image',
        'url',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
