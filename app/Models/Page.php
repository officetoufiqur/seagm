<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'title',
        'subtitle',
        'slug',
    ];

    public function home_cms()
    {
        return $this->hasMany(HomeCMS::class);
    }

    public function advantages()
    {
        return $this->hasMany(Advantage::class);
    }

    public function hero_section()
    {
        return $this->hasMany(HeroSection::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }
    
}
