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
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'mobile' => '1234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'status' => 'Active',
            'employee_code' => 'ADM001',
        ]);
        
        Admin::create([
            'name' => 'Staff User',
            'email' => 'staff@gmail.com',
            'mobile' => '0987654321',
            'role' => 'staff',
            'password' => Hash::make('password'),
            'status' => 'Active',
            'employee_code' => 'STF001',
        ]);
    }
}
