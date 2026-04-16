<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    protected $fillable = [
        'name',
        'category_id',
        'slug',
    ];

    public function category()
    {
        return $this->belongsTo(UserGuideCategory::class, 'category_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'sub_category_id');
    }
}
