<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleFeedback extends Model
{
    protected $table = 'article_feedback';

    protected $fillable = [
        'article_id',
        'is_helpful',
        'ip_address',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
