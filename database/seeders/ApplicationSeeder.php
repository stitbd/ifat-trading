<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('applications')->insert([
            [
                'company_name' => 'Tech Corp',
                'company_email' => 'info@techcorp.com',
                'address' => '1234 Tech St, Silicon Valley, CA',
                'about_company' => 'Tech Corp is a leading technology company specializing in software development.',
                'phone' => '123-456-7890',
                'google_map' => 'https://goo.gl/maps/example',
                'company_website_link' => 'https://www.techcorp.com',
                'logo' => 'techcorp_logo.png',
                'fav_icon' => 'techcorp_footer_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
