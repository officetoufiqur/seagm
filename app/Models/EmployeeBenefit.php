<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBenefit extends Model
{
    protected $table = 'employee_benefits';

    protected $fillable = [
        'title',
        'description',
        'icon',
    ];
}
