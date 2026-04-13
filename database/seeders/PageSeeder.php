<?php

namespace Database\Seeders;

use App\Models\Page;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::insert([
            [
                'title' => 'What we can do?',
                'subtitle' => 'With over 15 years of experience under our belt, here’s what SEAGM can offer your brand',
                'slug' => 'services',
            ],
            [
                'title' => 'Our Advantages',
                'subtitle' => 'With over 15 years of experience under our belt, here’s what SEAGM can offer your brand',
                'slug' => 'advantages',
            ],
            [
                'title' => 'Power up with SEAGM',
                'subtitle' => 'With over 15 years of experience under our belt, here’s what SEAGM can offer your brand',
                'slug' => 'power-up',
            ],
            [
                'title' => 'Global Convenient Access for Everyone',
                'subtitle' => null,
                'slug' => 'global-access',
            ],
            [
                'title' => 'Team trusted by the most exciting companies around the globe',
                'subtitle' => 'With over 15 years of experience under our belt, here is what SEAGM can offer your brand.',
                'slug' => 'brands',
            ],
            [
                'title' => 'Reach more people through us',
                'subtitle' => 'Equip with all-rounded marketing tools, we can elevate your brand globally.',
                'slug' => 'through-us',
            ]
        ]);
    }
}