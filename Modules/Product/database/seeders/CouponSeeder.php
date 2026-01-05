<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Modules\Product\Models\Coupon::insert([
            [
                'code' => 'WELCOME10',
                'type' => 'percent',
                'value' => 10.00,
                'status' => true,
                'start_date' => now(),
                'expiry_date' => now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FLAT50',
                'type' => 'fixed',
                'value' => 50.00,
                'status' => true,
                'start_date' => now(),
                'expiry_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SUMMERSALE',
                'type' => 'percent',
                'value' => 25.00,
                'status' => true,
                'start_date' => now(),
                'expiry_date' => now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'code' => 'EXPIRED',
                'type' => 'percent',
                'value' => 5.00,
                'status' => false,
                'start_date' => now()->subMonths(2),
                'expiry_date' => now()->subMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
