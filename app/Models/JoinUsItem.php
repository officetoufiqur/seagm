<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinUsItem extends Model
{
    protected $table = 'join_us_items';

    protected $fillable = [
        'join_us_id',
        'title',
    ];

    public function joinUs()
    {
        return $this->belongsTo(JoinUs::class);
    }
}
