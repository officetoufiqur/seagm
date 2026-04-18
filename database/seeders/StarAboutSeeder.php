<?php

namespace Database\Seeders;

use App\Models\Carousel;
use App\Models\StarAbout;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StarAboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StarAbout::insert([
            [
                'section' => 'about',
                'title' => 'Rookie',
                'subtitle' => 'LV1',
                'description' => 'Sign up as SEAGM Member',
                'image' => 'assets/images/about_card.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'cards',
                'title' => 'SEAGM STAR Member Levels',
                'subtitle' => 'The STAR Rewards Program consists of 4 levels. You can instantly upgrade to AMATEUR, PRO, or ELITE level by meeting the minimum spending requirement for each level.',
                'description' => 'Member level duration renews at every 90 days upon signed up as SEAGM Member. More rewards unlocked for higher level.',
                'image' => 'assets/images/about.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Carousel::create([
            'image' => 'assets/images/carousel.jpeg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
