<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutContact extends Model
{
    protected $table = 'about_contacts';

    protected $fillable = [
        'title',
        'subtitle',
        'image',
    ];
}
