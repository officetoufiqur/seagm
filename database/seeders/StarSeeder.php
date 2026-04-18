<?php

namespace Database\Seeders;

use App\Models\Star;
use App\Models\StarItem;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Star::insert([
            [
                'heading' => 'SEAGM STAR',
                'title' => 'REWARDS FOR MVPS',
                'subtitle' => 'As a token of appreciation, we would like to reward you everytime you top-up from us. Earn STAR with every 100 SEAGM credits you spent with us and enjoy rewards with the STARS.',
                'image' => 'assets/images/star.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        StarItem::insert([
            [
                'star_id' => 1,
                'title' => 'EARN',
                'subtitle' => '1 STAR with every 100 SEAGM Credits spent',
                'image' => 'assets/images/star-item-2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'star_id' => 1,
                'title' => 'REDEEM',
                'subtitle' => 'Rewards with STARs',
                'image' => 'assets/images/star-item-1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'star_id' => 1,
                'title' => 'ENJOY',
                'subtitle' => 'Benefits at different member levels',
                'image' => 'assets/images/star-item-3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
