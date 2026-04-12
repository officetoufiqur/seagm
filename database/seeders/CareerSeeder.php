<?php

namespace Database\Seeders;

use App\Models\Career;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Career::insert([
            [
                'section' => 'career',
                'title' => 'Join Our Team',
                'subtitle' => 'Here at SEAGM, we value excellence, integrity, respect and teamwork. With a headquarter in Sitiawan, we started in a small town with a big dream – to become the best digital goods distribution platform while being a customer-centric company that empower gamers to make a living from gaming. We now have offices all over Asia including Kuala Lumpur, China and Thailand.',
            ],
            [
                'section' => 'employee_benefits',
                'title' => 'Employee Benefits',
                'subtitle' => 'Here’s what you can expect if you’re part of the team:',
            ],
            [
               'section' => 'join_us',
               'title' => 'Join us today',
               'subtitle' => 'If our belief and culture is one that resonates with you, we want you on the team!',
            ]
        ]);
    }
}
