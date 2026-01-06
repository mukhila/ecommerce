<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Analytics Permissions
            ['name' => 'view_analytics', 'display_name' => 'View Analytics Dashboard', 'category' => 'analytics', 'description' => 'Access to analytics dashboard with basic metrics'],
            ['name' => 'view_financial_reports', 'display_name' => 'View Financial Reports', 'category' => 'analytics', 'description' => 'View detailed revenue and financial analytics'],
            ['name' => 'view_customer_analytics', 'display_name' => 'View Customer Analytics', 'category' => 'analytics', 'description' => 'View customer data and lifetime value'],
            ['name' => 'export_reports', 'display_name' => 'Export Reports (PDF/Excel)', 'category' => 'analytics', 'description' => 'Download and export analytics reports'],

            // Order Permissions
            ['name' => 'manage_orders', 'display_name' => 'Manage Orders', 'category' => 'orders', 'description' => 'View and update order information'],
            ['name' => 'delete_orders', 'display_name' => 'Delete/Cancel Orders', 'category' => 'orders', 'description' => 'Cancel and delete orders'],
            ['name' => 'update_order_status', 'display_name' => 'Update Order Status', 'category' => 'orders', 'description' => 'Change order status and tracking'],

            // Product Permissions
            ['name' => 'manage_products', 'display_name' => 'Manage Products', 'category' => 'products', 'description' => 'Create, edit, and delete products'],
            ['name' => 'view_products', 'display_name' => 'View Products (Read-Only)', 'category' => 'products', 'description' => 'View product information without editing'],

            // Admin Management
            ['name' => 'manage_admins', 'display_name' => 'Manage Admin Users', 'category' => 'admin', 'description' => 'Create and manage admin accounts'],
            ['name' => 'manage_settings', 'display_name' => 'Manage Company Settings', 'category' => 'admin', 'description' => 'Access company settings and configuration'],
        ];

        foreach ($permissions as $permission) {
            DB::table('admin_permissions')->insert([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'category' => $permission['category'],
                'description' => $permission['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign all permissions to admin role
        $allPermissions = DB::table('admin_permissions')->pluck('id');
        foreach ($allPermissions as $permissionId) {
            DB::table('admin_role_permissions')->insert([
                'role' => 'admin',
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign limited permissions to staff role
        $staffPermissions = DB::table('admin_permissions')
            ->whereIn('name', [
                'view_analytics',
                'view_products',
                'update_order_status',
                'manage_orders'
            ])
            ->pluck('id');

        foreach ($staffPermissions as $permissionId) {
            DB::table('admin_role_permissions')->insert([
                'role' => 'staff',
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Admin permissions seeded successfully!');
    }
}
