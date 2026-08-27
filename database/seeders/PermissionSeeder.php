<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // ===== Dashboard =====
            [
                'group_name' => 'Dashboard',
                'permissions' => [
                    'dashboard.view',
                ]
            ],

            // ===== User =====
            [
                'group_name' => 'User',
                'permissions' => [
                    'user.view',
                    'user.create',
                    'user.edit',
                    'user.delete',
                    'user.profile',
                    'user.profile.update',
                ]
            ],

            // ===== Role =====
            [
                'group_name' => 'Role',
                'permissions' => [
                    'role.view',
                    'role.create',
                    'role.edit',
                    'role.delete',
                    'role.permissions',
                ]
            ],

            // ===== System Settings =====
            [
                'group_name' => 'System Settings',
                'permissions' => [
                    'system_settings.view',
                    'system_settings.edit',
                ]
            ],

        ];

        foreach ($permissions as $group) {
            foreach ($group['permissions'] as $permission) {
                Permission::firstOrCreate(
                    ['name' => $permission, 'guard_name' => 'web'],
                    ['group_name' => $group['group_name']]
                );
            }
        }
    }
}
