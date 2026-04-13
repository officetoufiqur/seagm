<?php

namespace Database\Seeders;

use App\Models\Milestone;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MilestoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Milestone::insert([
            [
                'year' => 2016,
                'title' => 'More than 4 million orders have been completed since inception.',
                'image' => null,
            ],
            [
                'year' => 2014,
                'title' => '1.5 million unique visitors.',
                'image' => null,
            ]
        ]);
    }
}
