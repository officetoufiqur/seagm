<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleStep extends Model
{
    protected $table = 'article_steps';

    protected $fillable = [
        'article_id',
        'description',
        'image',
        'order',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
