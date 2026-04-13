<?php

namespace Database\Seeders;

use App\Models\Advantage;
use App\Models\AdvantageCard;
use Illuminate\Database\Seeder;

class AdvantageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Advantage::insert([
            [
                'page_id' => 2,
                'label' => 'website pageviews',
                'value' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => 2,
                'label' => 'unique visitors',
                'value' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        AdvantageCard::insert([
            [
                'title' => 'Penetrate and access new markets',
                'description' => 'We believe that the key to accessing the global market is having the right payment channels for local markets. That is why SEAGM has over 250+ and growing global payment channels to get your brand where it needs to be',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
