<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\SubCategory;
use App\Models\UserGuideCategory;
use Illuminate\Database\Seeder;

class GuideCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = UserGuideCategory::create([
            'name' => 'FAQS',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-question-mark-icon lucide-circle-question-mark"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>',
            'description' => 'Common questions with quick answers.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $subCategory = SubCategory::create([
            'category_id' => $category->id,
            'name' => 'Official Game Support Guides',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Article::create([
            'sub_category_id' => $subCategory->id,
            'title' => 'Avoid Order Regrets: Check Product Availability in Your Country Before You Buy.',
            'content' => 'Buying digital products online can be convenient and exciting, but nothing is more frustrating than discovering your purchase doesn’t work due to regional restrictions. Many digital products on SEAGM are region-locked, meaning they can only be used in certain countries or with accounts registered in specific regions. To make sure your purchase works as intended, follow these simple steps:',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
