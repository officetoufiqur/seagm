<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\DepartmentItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::insert([
            [
                "description" => "Since its inception, with more than 300 million users accessing our services, SEAGM continues to be the fastest-growing e-commerce platform focusing on digitals goods in Malaysia and has millions of users worldwide. As we continue to grow, SEAGM visions to be the world's leading digital goods and services platform by achieving excellence in profit, community, and lifestyle. We also aspire to give back to the gaming community while continuously working on building a sustainable ecosystem in gaming within our corporation. The company is determined to empower gamers' gaming experiences by providing convenient access to our services globally to enjoy games anytime, anywhere. Finally, we aim to be a customer-centric company that grows together with its community and employees.",
                "image" => 'https://seagm-media.seagmcdn.com/corp/art/pic_department.png?x-oss-process=image/resize,w_400/format,webp',
                "created_at" => now(),
                "updated_at" => now(),
            ]
        ]);

        DepartmentItem::insert([
            [
                "department_id" => 1,
                "title" => "SEA GAMER MALL SDN BHD",
                "subtitle" => "SEAGM's headquarter is located in Sitiawan, Perak, Malaysia. The three-story building houses 80 employees working under various departments, including the Administrative Department, Customer Service Team, Operations Team, Human Resource Department, and Sales Department. It also has a second office in Malaysia located in MSC designated cyber city, Kuala Lumpur.",
                "image" => 'assets/images/department.png',
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "department_id" => 1,
                "title" => "SEAGM China",
                "subtitle" => "SEAGM also has a technology department located in Chengdu, China. This elite team is responsible for the entire company's technology development and ensures top-notch performance to achieve the highest operational efficiency.",
                "image" => 'assets/images/department2.png',
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ]);
    }
}
