<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';

    protected $fillable = [
        'page_id',
        'logo',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
