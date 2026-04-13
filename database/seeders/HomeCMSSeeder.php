<?php

namespace Database\Seeders;

use App\Models\HomeCMS;
use Illuminate\Database\Seeder;

class HomeCMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HomeCMS::insert([
            [
                'page_id' => 1,
                'section' => 'services',
                'title' => 'One Platform, Global Coverage',
                'description' => 'A single integration unlocks global & local alternative payment methods - Cards, Online Banking, Bank Transfer, Cash, E-wallets, Vouchers & Carrier Billing.',
                'image' => 'https://corp.seagm.com/skin/images/art/icon_mission-372a4dedcc.svg',
                'icon' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => 3,
                'section' => 'power-up',
                'title' => 'Global Market Access',
                'description' => "SEAGM has 100+ global payment channels giving everyone access to the world's market.",
                'image' => null,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe-icon lucide-globe"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => 4,
                'section' => 'global-access',
                'title' => 'Digital Codes',
                'description' => 'Site-wide currency that can be used for purchasing any products on SEAGM.',
                'image' => null,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code-icon lucide-qr-code"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => 6,
                'section' => 'through-us',
                'title' => 'Website',
                'description' => 'Reach our 11 Million + and growing active users, get directly introduced to the entertainment industry.',
                'image' => 'https://corp.seagm.com/skin/images/art/pic_social_1-daad0cf2e3.svg',
                'icon' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => 7,
                'section' => 'awards',
                'title' => 'The Star Outstanding Business Awards 2022',
                'description' => 'Best in Brand - Meritorious Awards',
                'image' => 'https://seagm-media.seagmcdn.com/corp/award/soba-2022.png?x-oss-process=image/resize,l_300/format,webp',
                'icon' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
