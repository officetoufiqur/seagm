<?php

namespace Database\Seeders;

use App\Models\HeroSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       HeroSection::insert([
           [
              'heading' => 'Grow Your Revenue & Gain Access To New Markets And Reach Global Users',
              'title' => 'With SEAGM - the pioneer of digital content monetization',
              'image' => 'https://example.com/hero-image.jpg',
              'background_image' => 'https://example.com/hero-background-image.jpg',
           ]
       ]);
    }
}
