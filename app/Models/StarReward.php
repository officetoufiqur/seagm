<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StarReward extends Model
{
    protected $table = 'star_rewards';

    protected $fillable = [
        'star_category_id',
        'title',
        'subtitle',
        'coupon',
        'reward',
        'description',
        'image',
    ];

    public function starCategory()
    {
        return $this->belongsTo(StarCategory::class);
    }
}
