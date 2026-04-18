<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StarCategory extends Model
{
    protected $table = 'star_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function rewards()
    {
        return $this->hasMany(StarReward::class);
    }
}
