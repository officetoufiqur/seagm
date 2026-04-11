<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsBanner extends Model
{
    protected $table = 'news_banners';

    protected $fillable = [
        'news_id',
        'title',
        'image',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
