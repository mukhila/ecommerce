<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'mobile' => '1234567890',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'status' => 'Active',
                'employee_code' => 'ADM001',
            ]
        );
        
        Admin::updateOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff User',
                'mobile' => '0987654321',
                'role' => 'staff',
                'password' => Hash::make('password'),
                'status' => 'Active',
                'employee_code' => 'STF001',
            ]
        );
    }
}
