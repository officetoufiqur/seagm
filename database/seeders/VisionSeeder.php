<?php

namespace Database\Seeders;

use App\Models\Vision;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Vision::insert([
            [
                'description' => "The vision for SEAGM is simple, to be the world's leading digital goods and services platform, and to achieve this, we have established clear goals:",
                'created_at' => now(),
                'updated_at' => now(),
            ],
       ]);

         $vision = Vision::first();

        $vision->items()->insert([
            [
                'vision_id' => $vision->id,
                'title' => "Community",
                'subtitle' => "SEAGM intends to give back to the gaming community by collaborating with game industry players for talent development and creating potential career paths for gamers.",
                'image' => "assets/images/vision.png",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vision_id' => $vision->id,
                'title' => "Ecosystem",
                'subtitle' => "We aspire to create a well-rounded and fully equipped environment to best support gamers’ needs by providing high quality and quantity of digital goods alongside easily accessible payment methods.",
                'image' => "assets/images/vision1.png",
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
