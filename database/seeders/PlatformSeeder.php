<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Platform::insert([
            [
                'section' => 'platform',
                'title' => 'SEAGM.com is a digital goods platform that retails gaming products and services to individuals and businesses while providing top-notch service and reasonable prices. Our platform runs on an easy-to-use UI with multi-language support, extensive payment methods, and 24/7 Live Chat support that ensures an enriching and convenient shopping experience for all our users.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'kaleoz',
                'title' => 'KALEOZ.com bridges gamers together - specifically those who want to sell and buy digital goods. Our platform functions as an outlet that enables enthusiastic gamers to turn their hobby into a business by providing a trading platform with a series of localized languages, payment methods, and user-friendly features and tools.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'news',
                'title' => 'News.seagm is a content platform covering the latest happenings in the gaming and esports industry. Our content platform functions as an information gateway for anything and everything happening in the industry. Additionally, it also includes coverage of the company’s involvement in the industry and an archive of our footprints in esports.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
