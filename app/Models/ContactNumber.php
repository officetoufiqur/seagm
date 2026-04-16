<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactNumber extends Model
{
    protected $table = 'contact_numbers';

    protected $fillable = [
        'title',
        'subtitle',
        'numbers',
    ];

    protected $casts = [
        'numbers' => 'array',
    ];
}
