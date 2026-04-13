<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advantage extends Model
{
    protected $table = 'advantages';

    protected $fillable = [
        'page_id',
        'label',
        'value'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
