<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $table = 'about_us';

    protected $fillable = [
        'title',
        'description',
        'page_view',
        'unique_visitors',
        'registered_users',
        'active_users',
        'subscribers',
        'image',
    ];
}
