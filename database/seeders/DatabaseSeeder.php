<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key' => 'business_note',
                'value' => '',
            ],
            [
                'key' => 'total_investment',
                'value' => 0,
                'type' => 'TOTAL_INVESTMENT'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate([
                'key' => $setting['key'],
            ], [
                'value' => $setting['value'],
                'type' => $setting['type'] ?? null,
            ]);
        }

        $permissions = [
            'dashboard',

            'customer-view',
            'customer-create',
            'customer-update',
            'customer-delete',

            'business-view',
            'business-create',
            'business-update',

            'debit-view',
            'debit-update',

            'user-view',
            'user-create',
            'user-update',
            'user-delete',

            'role-view',
            'role-create',
            'role-update',
            'role-delete',

            'machine-view',
            'machine-create',
            'machine-update',
            'machine-delete',

            'collaborator-view',
            'collaborator-create',
            'collaborator-update',
            'collaborator-delete',

            'activity-log-view',
            'activity-log-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                [
                    'name' => $permission,
                ],
                [
                    'name' => $permission,
                    'guard_name' => 'web',
                ]
            );
        }

        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $role->givePermissionTo($permissions);

        $user = User::firstOrCreate(
            [
                'email' => 'admin@admin.com',
            ],
            [
                'name' => 'admin',
                'password' => Hash::make('Gavosong800@'),
            ]
        );

        $user->assignRole('admin');
    }
}
