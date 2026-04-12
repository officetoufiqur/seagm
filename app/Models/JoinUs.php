<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinUs extends Model
{
    protected $table = 'join_us';

    protected $fillable = [
        'title',
        'icon',
    ];

    public function items()
    {
        return $this->hasMany(JoinUsItem::class);
    }
}
