<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentItem extends Model
{
    protected $table = 'department_items';

    protected $fillable = [
        'department_id',
        'title',
        'subtitle',
        'image',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
