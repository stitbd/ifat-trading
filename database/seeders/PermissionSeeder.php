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
            [
                'group_name' => 'Product Type',
                'permissions' => [
                    'product_type.view',
                    'product_type.create',
                    'product_type.edit',
                    'product_type.delete',
                    'product_type.approve',
                ]
            ],
            [
                'group_name' => 'Vehicle Type',
                'permissions' => [
                    'vehicle_type.view',
                    'vehicle_type.create',
                    'vehicle_type.edit',
                    'vehicle_type.delete',
                    'vehicle_type.approve',
                ]
            ],
            [
                'group_name' => 'Product Size',
                'permissions' => [
                    'product_size.view',
                    'product_size.create',
                    'product_size.edit',
                    'product_size.delete',
                    'product_size.approve',
                ]
            ],
            [
                'group_name' => 'Vat Percentage',
                'permissions' => [
                    'vat_percentage.view',
                    'vat_percentage.create',
                    'vat_percentage.edit',
                    'vat_percentage.delete',
                    'vat_percentage.approve',
                ]
            ],
            [
                'group_name' => 'Warranty Period',
                'permissions' => [
                    'warranty_period.view',
                    'warranty_period.create',
                    'warranty_period.edit',
                    'warranty_period.delete',
                    'warranty_period.approve',
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
