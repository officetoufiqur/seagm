<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThroughUsItem extends Model
{
    protected $table = 'through_us_items';

    protected $fillable = [
        'title',
        'icon',
    ];
}
