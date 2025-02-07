<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

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
        ];

        // foreach ($settings as $setting) {
        //     Setting::create($setting);
        // }

        // User::create([
        //     'name' => 'admin',
        //     'email' => 'admin@admin.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('password'),
        //     'remember_token' => Str::random(10),
        // ]);

        $permissions = [
            'dashboard',

            'customer-view',
            'customer-create',
            'customer-update',
            'customer-delete',

            'business-view',
            'business-create',
            'business-update',
            'business-delete',

            'debit-view',
            'debit-create',
            'debit-update',
            'debit-delete',

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
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => $permission === 'dashboard' || strpos($permission, 'view') !== false ? 'web' : 'sanctum',
            ]);
        }
    }
}
