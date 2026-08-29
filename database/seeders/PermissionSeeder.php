<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {

        $roleSuperAdmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);
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
            [
                'group_name' => 'Wing',
                'permissions' => [
                    'wing.view',
                    'wing.create',
                    'wing.edit',
                    'wing.delete',
                    'wing.approve',

                ]
            ],
            [
                'group_name' => 'Categories',
                'permissions' => [
                    'category.view',
                    'category.create',
                    'category.edit',
                    'category.delete',
                    'category.approve',

                ]
            ],
            [
                'group_name' => 'SubCategories',
                'permissions' => [
                    'subcategory.view',
                    'subcategory.create',
                    'subcategory.edit',
                    'subcategory.delete',
                    'subcategory.approve',

                ]
            ],
                 [
                'group_name' => 'Brand',
                'permissions' => [
                    'brand.view',
                    'brand.create',
                    'brand.edit',
                    'brand.delete',
                    'brand.approve',

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

                $roleSuperAdmin->givePermissionTo($permission);
            }
        }
    }
}
