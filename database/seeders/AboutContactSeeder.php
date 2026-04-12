<?php

namespace Database\Seeders;

use App\Models\AboutContact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutContact::insert([
            [
              'title' => 'Follow us',
              'subtitle' => 'Get to know us better through our social channels and see what goes on at SEAGM!',
              'image' => 'https://example.com/about-us-image.jpg',
            ]
        ]);
    }
}
