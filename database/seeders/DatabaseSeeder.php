<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
                'key' => '35',
                'value' => 33000000,
            ],
            [
                'key' => '35',
                'value' => 34000000,
            ],
            [
                'key' => '50',
                'value' => 48000000,
            ],
            [
                'key' => '50',
                'value' => 49000000,
            ],
            [
                'key' => '100',
                'value' => 98000000,
            ],
            [
                'key' => '100',
                'value' => 99000000,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
    }
}
