<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\CompanySetting;

class CompanySettingSeeder extends Seeder
{
    /**
     * Seed the company settings for header and footer.
     */
    public function run(): void
    {
        // Clear existing settings
        CompanySetting::truncate();

        CompanySetting::create([
            'company_name' => 'JangaKids',
            'logo' => 'settings/logo.png',
            'address' => 'JangaKids Store, India',
            'phone' => '+91 123-456-7890',
            'email' => 'support@jangakids.com',
            'whatsapp_no' => '+91 987-654-3210',
            'social_links' => [
                'facebook' => 'https://www.facebook.com/jangakids',
                'twitter' => 'https://www.twitter.com/jangakids',
                'instagram' => 'https://www.instagram.com/jangakids',
                'pinterest' => 'https://www.pinterest.com/jangakids',
                'youtube' => 'https://www.youtube.com/jangakids',
                'linkedin' => 'https://www.linkedin.com/company/jangakids',
            ],
        ]);

        $this->command->info('Company settings seeded successfully!');
    }
}
