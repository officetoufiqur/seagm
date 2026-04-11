<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use Illuminate\Database\Seeder;

class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutUs::insert([
            'title' => 'Introduction',
            'description' => 'Founded in 2007, SEAGM had a singular goal in mind: to develop the best online games digital goods marketplace in e-commerce that could eventually help pave the way for gamers to make a living through their hobby of gaming. To achieve this, we needed a team defined by specific core values to deliver the best experience to our users. These values are Integrity, Communication, Care, and Excellence in all things.',
            'page_view' => 177,
            'unique_visitors' => 11,
            'registered_users' => 5.2,
            'active_users' => 118,
            'subscribers' => 700000,
            'image' => 'https://example.com/about-us-image.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
