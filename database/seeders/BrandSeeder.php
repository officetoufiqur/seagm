<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::insert([
            [
                'page_id' => 5,
                'logo' => '/assets/images/logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => 5,
                'logo' => '/assets/images/logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
