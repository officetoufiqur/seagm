<?php

namespace Database\Seeders;

use App\Models\NewsBanner;
use Illuminate\Database\Seeder;

class NewsBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewsBanner::insert([
            [
                'news_id' => 1,
                'title' => 'PUBG Mobile – How to Top-Up UC (Unknown Cash) with SEAGM',
                'image' => 'https://news.seagm.com/wp-content/uploads/2026/04/PUBG-Top-Up-Thumb-2.jpg',
            ],
            [
                'news_id' => 2,
                'title' => 'MLBB × NARUTO Returns—Get Minato & Itachi Skins Now',
                'image' => 'https://news.seagm.com/wp-content/uploads/2026/04/MLBB-%C3%97-NARUTO-Returns%E2%80%94Get-Minato-Itachi-Skins-Now.png',
            ],
            [
                'news_id' => 3,
                'title' => 'PUBG Mobile – Update 4.3 “Evolving Universe” Update Patch Notes',
                'image' => 'https://news.seagm.com/wp-content/uploads/2026/04/1-Thumbnail-Alt-Update-4.3.jpg',
            ],
        ]);
    }
}
