<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\ContactNumber;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::insert([
            [
                'heading' => 'Contact us with phone call, text message or chat apps',
                'title' => 'Contact SEAGM customer support',
                'subtitle' => 'Need help? Contact our customer support using LiveChat for assistance. You can also reach us through phone calls, text messages, or the chatting apps listed below.',
                'image' => 'assets/images/contact.png',
                'address_title' => 'SEA Gamer Mall Sdn Bhd Office Address',
                'map' => '<iframe
                        src="https://www.google.com/maps?q=SEAGM&output=embed"
                        width="100%"
                        height="400"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        ContactNumber::create([
            'title' => 'For Malaysia and International Customers',
            'subtitle' => 'Customer support phone number (24 hours)',
            'numbers' => ['+60-124023320', '+60-124052343'],
        ]);

        ContactNumber::create([
            'title' => 'For Thailand Customers',
            'subtitle' => 'Customer support phone number (24 hours)',
            'numbers' => ['+66-822136014 (AIS)', '+66-814796345 (DTAC)'],
        ]);
    }
}
