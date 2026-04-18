<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StarAbout extends Model
{
    protected $table = 'star_abouts';

    protected $fillable = [
        'section',
        'title',
        'subtitle',
        'description',
        'image',
    ];
}
