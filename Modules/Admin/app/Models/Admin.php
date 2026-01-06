<?php

namespace Modules\Admin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Admin\Database\Factories\AdminFactory;

class Admin extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'role',
        'password',
        'status',
        'employee_code',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // protected static function newFactory(): AdminFactory
    // {
    //     // return AdminFactory::new();
    // }

    /**
     * Check if admin has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'admin') {
            return true; // Admins have all permissions
        }

        // Check database-driven permissions
        return \DB::table('admin_role_permissions')
            ->join('admin_permissions', 'admin_role_permissions.permission_id', '=', 'admin_permissions.id')
            ->where('admin_role_permissions.role', $this->role)
            ->where('admin_permissions.name', $permission)
            ->exists();
    }

    /**
     * Check if admin is staff
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Check if admin is super admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get all permissions for this admin's role
     */
    public function getPermissions(): array
    {
        if ($this->role === 'admin') {
            return \DB::table('admin_permissions')->pluck('name')->toArray();
        }

        return \DB::table('admin_role_permissions')
            ->join('admin_permissions', 'admin_role_permissions.permission_id', '=', 'admin_permissions.id')
            ->where('admin_role_permissions.role', $this->role)
            ->pluck('admin_permissions.name')
            ->toArray();
    }
}
