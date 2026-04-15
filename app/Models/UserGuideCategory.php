<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGuideCategory extends Model
{
    protected $table = 'user_guide_categories';

    protected $fillable = [
        'name',
        'icon',
        'description',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }
}
