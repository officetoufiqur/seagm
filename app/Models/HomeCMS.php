<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCMS extends Model
{

    protected $fillable = [
        'page_id',
        'section',
        'title',
        'description',
        'image',
        'icon',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
