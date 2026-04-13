<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvantageCard extends Model
{
    protected $table = 'advantage_cards';

    protected $fillable = [
        'title',
        'description',
    ];

}
