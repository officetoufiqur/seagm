<?php

namespace App\Helpers;

use App\Models\News;

class NewsByCategory
{
    public function get($slug, $limit)
    {
        return News::with('category', 'author:id,name,image')
            ->whereHas('category', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->latest()
            ->take($limit)
            ->get();
    }
}
