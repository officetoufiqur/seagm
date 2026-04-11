<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'content',
        'image',
        'published_at',
        'status',
        'comments',
    ];

    protected $casts = [
        'published_at' => 'date',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function newsBanners()
    {
        return $this->hasMany(NewsBanner::class);
    }
}
