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
            'company_name' => 'Multikart',
            'logo' => 'settings/logo.png',
            'address' => 'Multikart Demo Store, Demo Street, Demo City, India - 345659',
            'phone' => '+91 123-456-7890',
            'email' => 'support@multikart.com',
            'whatsapp_no' => '+91 987-654-3210',
            'social_links' => [
                'facebook' => 'https://www.facebook.com/multikart',
                'twitter' => 'https://www.twitter.com/multikart',
                'instagram' => 'https://www.instagram.com/multikart',
                'pinterest' => 'https://www.pinterest.com/multikart',
                'youtube' => 'https://www.youtube.com/multikart',
                'linkedin' => 'https://www.linkedin.com/company/multikart',
            ],
        ]);

        $this->command->info('Company settings seeded successfully!');
    }
}
