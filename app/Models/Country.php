<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'api_id',
        'icon',
        'code',
        'name',
        'calling_code',
    ];
}
