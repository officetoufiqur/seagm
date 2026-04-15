<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'sub_category_id',
        'title',
        'content',
        'views',
        'is_promoted',
    ];

    protected $casts = [
        'is_promoted' => 'boolean',
    ];
    
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function steps()
    {
        return $this->hasMany(ArticleStep::class, 'article_id');
    }

    public function feedback()
    {
        return $this->hasMany(ArticleFeedback::class, 'article_id');
    }
}
